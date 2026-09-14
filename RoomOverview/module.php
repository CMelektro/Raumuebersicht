<?php
class Raumübersicht extends IPSModule
{
    private const LIGHTS = ['Light1', 'Light2', 'Light3', 'Light4'];
    private const SOCKETS = ['Socket1', 'Socket2'];
    private const BLINDS = ['Blind1', 'Blind2'];

    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString('Title', 'Küche');
        $this->RegisterPropertyBoolean('ClimateEnabled', false);
        $this->RegisterPropertyInteger('ClimateActual', 0);
        $this->RegisterPropertyBoolean('ShowTitle', true);
        $this->RegisterPropertyString('RoomStyle', 'kitchen');
        $this->RegisterPropertyInteger('TargetCategory', 0);
        $this->RegisterPropertyInteger('BackgroundColor', 2105636);
        $this->RegisterPropertyInteger('TextColor', 16777215);
        $types = ['pendant', 'spots', 'led', 'floor'];
        foreach (self::LIGHTS as $i => $p) {
            $this->RegisterPropertyBoolean($p . 'Enabled', false);
            $this->RegisterPropertyString($p . 'Name', 'Lichtkreis ' . ($i + 1));
            $this->RegisterPropertyString($p . 'Type', $types[$i]);
            $this->RegisterPropertyInteger($p . 'Status', 0);
        }
        foreach (self::SOCKETS as $i => $p) {
            $this->RegisterPropertyBoolean($p . 'Enabled', false);
            $this->RegisterPropertyString($p . 'Name', 'Schaltkreis ' . ($i + 1));
            $this->RegisterPropertyInteger($p . 'Status', 0);
        }
        foreach (self::BLINDS as $i => $p) {
            $this->RegisterPropertyBoolean($p . 'Enabled', false);
            $this->RegisterPropertyString($p . 'Name', 'Rollladen ' . ($i + 1));
            $this->RegisterPropertyInteger($p . 'Status', 0);
            $this->RegisterPropertyInteger($p . 'Maximum', 100);
            $this->RegisterPropertyBoolean($p . 'Invert', false);
        }
        $this->SetVisualizationType(defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN')
            ? constant('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN') : 1);
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->SetVisualizationType(defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN')
            ? constant('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN') : 1);
        // Updating a visual title must never rename the instance.
        foreach ($this->GetReferenceList() as $id) $this->UnregisterReference($id);
        foreach ($this->GetMessageList() as $id => $messages) {
            foreach ($messages as $message) $this->UnregisterMessage($id, $message);
        }
        foreach ($this->VariableProperties() as $property) {
            $id = $this->ReadPropertyInteger($property);
            if (IPS_VariableExists($id)) {
                $this->RegisterReference($id);
                $this->RegisterMessage($id, VM_UPDATE);
            }
        }
        $target = $this->ReadPropertyInteger('TargetCategory');
        if (IPS_ObjectExists($target)) $this->RegisterReference($target);
        $this->UpdateVisualizationValue($this->Snapshot());
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        if ($Message === VM_UPDATE) $this->UpdateVisualizationValue($this->Snapshot());
    }

    public function GetVisualizationTile()
    {
        $html = str_replace('/*CM_EXTENSIONS*/', file_get_contents(__DIR__ . '/overview.js'), file_get_contents(__DIR__ . '/module.html'));
        $extra = '<style>' . file_get_contents(__DIR__ . '/overview.css') . '</style><script>window.handleMessage('
            . json_encode($this->Snapshot(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
            . ')</script>';
        return str_replace('</body>', $extra . '</body>', $html);
    }

    public function RequestAction($Ident, $Value)
    {
        throw new RuntimeException('Diese Kachel dient ausschließlich der Anzeige und Navigation.');
    }

    private function VariableProperties()
    {
        return array_merge(['ClimateActual'], array_map(fn($p) => $p . 'Status', array_merge(self::LIGHTS, self::SOCKETS, self::BLINDS)));
    }

    private function ReadStatus($property, $types)
    {
        $id = $this->ReadPropertyInteger($property);
        if (!IPS_VariableExists($id)) return null;
        $variable = IPS_GetVariable($id);
        if (!in_array($variable['VariableType'], $types, true)) return null;
        $value = GetValue($id);
        return is_float($value) && !is_finite($value) ? null : $value;
    }

    private function SceneImage()
    {
        $room = $this->ReadPropertyString('RoomStyle');
        $allowed = ['kitchen','hall','corridor','living','dining','guest_wc','children','bedroom','staircase','office','utility','technical','storage','neutral'];
        if (!in_array($room, $allowed, true)) $room = 'neutral';
        if ($room === 'neutral') $room = 'office';
        $path = __DIR__ . '/assets/rooms/' . $room . '.jpg';
        return is_file($path) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($path)) : '';
    }

    private function Snapshot()
    {
        // Retain the property name so existing category assignments survive updates.
        $target = $this->ReadPropertyInteger('TargetCategory');
        $targetMessage = '';
        $validTarget = $target > 0 && $target !== $this->InstanceID
            && (IPS_CategoryExists($target) || IPS_InstanceExists($target));
        // Symcon 9.0 cannot open HTML instances fullscreen. Navigate to their
        // nearest containing category instead; never send an instance to openObject.
        if ($validTarget && IPS_InstanceExists($target)
            && !defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN')) {
            $seen = [];
            while ($target > 0 && IPS_ObjectExists($target) && !isset($seen[$target])
                && !IPS_CategoryExists($target)) {
                $seen[$target] = true;
                $target = IPS_GetParent($target);
            }
            $validTarget = $target > 0 && IPS_CategoryExists($target);
            if (!$validTarget) $targetMessage = 'Die verknüpfte Instanz liegt in keiner Raumkategorie. Bitte eine in der Visu sichtbare Raumkategorie als Ziel auswählen.';
        }
        $result = [
            'sceneImage' => $this->SceneImage(),
            'title' => $this->ReadPropertyString('Title'), 'showTitle' => $this->ReadPropertyBoolean('ShowTitle'), 'showSummary' => false,
            'room' => $this->ReadPropertyString('RoomStyle'),
            'backgroundColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('BackgroundColor')),
            'textColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('TextColor')),
            'targetCategory' => $validTarget ? $target : 0,
            'targetMessage' => $targetMessage,
            'lights' => [], 'sockets' => [], 'blinds' => [],
            'climate' => ['enabled' => $this->ReadPropertyBoolean('ClimateEnabled'),
                'actual' => $this->ReadStatus('ClimateActual', [1,2])]
        ];
        foreach (self::LIGHTS as $i => $p) {
            $raw = $this->ReadStatus($p . 'Status', [0, 1, 2]);
            $on = $raw === null ? null : (is_bool($raw) ? $raw : (float)$raw > 0);
            $result['lights'][] = [
                'id' => 'L' . ($i + 1), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'),
                'name' => $this->ReadPropertyString($p . 'Name'), 'type' => $this->ReadPropertyString($p . 'Type'),
                'useSwitch' => false, 'useDim' => false, 'canSwitch' => false, 'canDim' => false,
                'on' => $on, 'level' => $on === null ? null : ($on ? 100 : 0),
                'x' => 0, 'y' => 0, 'scale' => 100, 'autoPosition' => true
            ];
        }
        foreach (self::SOCKETS as $i => $p) {
            $raw = $this->ReadStatus($p . 'Status', [0]);
            $result['sockets'][] = [
                'id' => 'S' . ($i + 1), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'),
                'name' => $this->ReadPropertyString($p . 'Name'), 'on' => $raw === null ? null : (bool)$raw,
                'canSwitch' => false
            ];
        }
        foreach (self::BLINDS as $i => $p) {
            $raw = $this->ReadStatus($p . 'Status', [1, 2]);
            $maximum = max(1, $this->ReadPropertyInteger($p . 'Maximum'));
            $position = $raw === null ? null : max(0, min(100, (float)$raw * 100 / $maximum));
            if ($position !== null && $this->ReadPropertyBoolean($p . 'Invert')) $position = 100 - $position;
            $result['blinds'][] = [
                'id' => 'B' . ($i + 1), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'),
                'name' => $this->ReadPropertyString($p . 'Name'), 'position' => $position,
                'useMove' => false, 'useStop' => false, 'usePosition' => false,
                'canMove' => false, 'canStop' => false, 'canPosition' => false
            ];
        }
        return json_encode($result, JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
