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
        // Symcon 9.0: HTML-SDK. Type 2 is available only from version 9.1.
        $this->SetVisualizationType(1);
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->SetVisualizationType(1);
        $title = trim($this->ReadPropertyString('Title'));
        if ($title !== '' && IPS_GetName($this->InstanceID) !== $title) IPS_SetName($this->InstanceID, $title);
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
        $html = file_get_contents(__DIR__ . '/module.html');
        $extra = '<style>' . file_get_contents(__DIR__ . '/overview.css') . '</style><script>'
            . file_get_contents(__DIR__ . '/overview.js') . '</script><script>handleMessage('
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
        return array_map(fn($p) => $p . 'Status', array_merge(self::LIGHTS, self::SOCKETS, self::BLINDS));
    }

    private function ReadStatus($property, $types)
    {
        $id = $this->ReadPropertyInteger($property);
        if (!IPS_VariableExists($id)) return null;
        $variable = IPS_GetVariable($id);
        if (!in_array($variable['VariableType'], $types, true)) return null;
        return GetValue($id);
    }

    private function Snapshot()
    {
        // Retain the property name so existing category assignments survive updates.
        $target = $this->ReadPropertyInteger('TargetCategory');
        $validTarget = $target > 0 && $target !== $this->InstanceID
            && (IPS_CategoryExists($target) || IPS_InstanceExists($target));
        $result = [
            'title' => $this->ReadPropertyString('Title'), 'showTitle' => $this->ReadPropertyBoolean('ShowTitle'), 'showSummary' => false,
            'room' => $this->ReadPropertyString('RoomStyle'),
            'backgroundColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('BackgroundColor')),
            'textColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('TextColor')),
            'targetCategory' => $validTarget ? $target : 0,
            'lights' => [], 'sockets' => [], 'blinds' => []
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
