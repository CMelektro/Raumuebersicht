<?php
error_reporting(E_ALL);
set_error_handler(fn($s,$m,$f,$l)=>throw new ErrorException($m,0,$s,$f,$l));
define('VM_UPDATE',10603);$variables=[];$checks=0;$names=[100=>'Übersicht'];
class IPSModule {
 public $InstanceID=100,$properties=[],$references=[],$messages=[],$updates=[],$visualization;
 public function Create(){} public function ApplyChanges(){}
 public function RegisterPropertyString($p,$v){$this->properties[$p]=$v;} public function RegisterPropertyBoolean($p,$v){$this->properties[$p]=$v;} public function RegisterPropertyInteger($p,$v){$this->properties[$p]=$v;}
 public function ReadPropertyString($p){return $this->properties[$p];} public function ReadPropertyBoolean($p){return $this->properties[$p];} public function ReadPropertyInteger($p){return $this->properties[$p];}
 public function GetReferenceList(){return array_keys($this->references);} public function RegisterReference($id){$this->references[$id]=true;} public function UnregisterReference($id){unset($this->references[$id]);}
 public function GetMessageList(){return $this->messages;} public function RegisterMessage($id,$m){$this->messages[$id]=[$m];} public function UnregisterMessage($id,$m){unset($this->messages[$id]);}
 public function SetVisualizationType($v){$this->visualization=$v;} public function UpdateVisualizationValue($v){$this->updates[]=json_decode($v,true,512,JSON_THROW_ON_ERROR);}
}
function IPS_GetName($id){return $GLOBALS['names'][$id];} function IPS_SetName($id,$n){$GLOBALS['names'][$id]=$n;}
function IPS_VariableExists($id){return isset($GLOBALS['variables'][$id]);} function IPS_GetVariable($id){return $GLOBALS['variables'][$id];} function GetValue($id){return $GLOBALS['variables'][$id]['value'];}
function IPS_ObjectExists($id){return $id===500||IPS_InstanceExists($id)||IPS_VariableExists($id);} function IPS_CategoryExists($id){return $id===500;}
function IPS_InstanceExists($id){return in_array($id,[100,600],true);}
function eq($a,$b,$why){$GLOBALS['checks']++;if($a!==$b)throw new Exception($why.' expected '.json_encode($b).' got '.json_encode($a));}
function variable($id,$type,$value){$GLOBALS['variables'][$id]=['VariableType'=>$type,'value'=>$value];}
require __DIR__.'/../RoomOverview/module.php';
$m=new Raumübersicht();$m->Create();eq($m->visualization,1,'Symcon 9.0 HTML tile');
$m->properties['Title']='Wohnzimmer';$m->properties['RoomStyle']='living';$m->properties['TargetCategory']=500;
foreach(range(1,4) as $i){$m->properties['Light'.$i.'Enabled']=true;$m->properties['Light'.$i.'Status']=100+$i;variable(100+$i,$i===1?0:1,$i%2?true:0);}
foreach(range(1,2) as $i){$m->properties['Socket'.$i.'Enabled']=true;$m->properties['Socket'.$i.'Status']=200+$i;variable(200+$i,0,$i===1);$m->properties['Blind'.$i.'Enabled']=true;$m->properties['Blind'.$i.'Status']=300+$i;variable(300+$i,1,$i===1?25:100);}
$m->ApplyChanges();$s=end($m->updates);eq($names[100],'Wohnzimmer','instance renamed');eq($s['room'],'living','room type');eq($s['targetCategory'],500,'target');eq(array_column($s['lights'],'on'),[true,false,true,false],'light states');eq(array_column($s['sockets'],'on'),[true,false],'socket states');eq(array_map('intval',array_column($s['blinds'],'position')),[25,100],'blind positions');
$before=count($m->updates);$variables[102]['value']=50;$m->MessageSink(0,102,VM_UPDATE,[]);eq(count($m->updates),$before+1,'live update');eq(end($m->updates)['lights'][1]['on'],true,'numeric light on');
$m->properties['TargetCategory']=999;$m->ApplyChanges();eq(end($m->updates)['targetCategory'],0,'invalid category blocked');
$m->properties['TargetCategory']=600;$m->ApplyChanges();eq(end($m->updates)['targetCategory'],600,'room instance accepted');eq(isset($m->references[600]),true,'target instance referenced');
$m->properties['TargetCategory']=100;$m->ApplyChanges();eq(end($m->updates)['targetCategory'],0,'self target blocked');
$m->properties['TargetCategory']=102;$m->ApplyChanges();eq(end($m->updates)['targetCategory'],0,'variable target blocked');
$m->properties['TargetCategory']=500;$m->ApplyChanges();eq(end($m->updates)['targetCategory'],500,'legacy category still accepted');
$m->properties['Light1Status']=999;$m->ApplyChanges();eq(end($m->updates)['lights'][0]['on'],null,'missing status unknown');
$m->properties['Blind1Maximum']=255;$variables[301]['value']=128;$m->properties['Blind1Invert']=true;$m->ApplyChanges();$p=end($m->updates)['blinds'][0]['position'];eq(abs($p-49.80392156862745)<0.0001,true,'blind scaling and inversion');
$form=json_decode(file_get_contents(__DIR__.'/../RoomOverview/form.json'),true,512,JSON_THROW_ON_ERROR);$namesInForm=[];$walk=function($nodes)use(&$walk,&$namesInForm){foreach($nodes as $n){if(isset($n['name']))$namesInForm[]=$n['name'];if(isset($n['items']))$walk($n['items']);}};$walk($form['elements']);sort($namesInForm);$props=array_keys($m->properties);sort($props);eq($namesInForm,$props,'form covers all properties');
$m->properties['Title']='</script><img src=x onerror=alert(1)>';$html=$m->GetVisualizationTile();eq(strpos($html,$m->properties['Title']),false,'safe title injection');eq(strlen($html)<1000000,true,'below size limit');
try{$m->RequestAction('anything',true);throw new Exception('action accepted');}catch(RuntimeException $e){$checks++;}
eq($s['climate']['enabled'],false,'temperature hidden by default');
$m->properties['ClimateEnabled']=true;$m->properties['ClimateActual']=45000;variable(45000,2,21.5);$m->ApplyChanges();
eq(end($m->updates)['climate']['actual'],21.5,'actual temperature');eq(isset($m->references[45000]),true,'temperature referenced');
$variables[45000]['value']=20.5;$m->MessageSink(0,45000,VM_UPDATE,[]);eq(end($m->updates)['climate']['actual'],20.5,'temperature updated live');
$m->properties['ClimateEnabled']=false;$m->ApplyChanges();eq(end($m->updates)['climate']['enabled'],false,'overview temperature independently hidden');
$variables[45000]['value']=INF;$m->ApplyChanges();eq(end($m->updates)['climate']['actual'],null,'nonfinite temperature safe');
$m->properties['ClimateActual']=0;$m->ApplyChanges();eq(end($m->updates)['climate']['actual'],null,'missing sensor unknown');eq(isset($m->references[45000]),false,'removed sensor unsubscribed');
try{$m->RequestAction('ClimateTarget',22);throw new Exception('temperature write accepted');}catch(RuntimeException $e){$checks++;}
echo "PASS: $checks backend assertions, HTML ".strlen($html)." bytes.\n";
