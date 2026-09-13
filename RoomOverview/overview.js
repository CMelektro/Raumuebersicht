'use strict';
buildCard=function(){};
const renderRoom=handleMessage,tile=$('tile');
tile.classList.add('overview');tile.setAttribute('role','link');
const head=el('div',{class:'overview-head'},tile);tile.prepend(head);
const roomTitle=el('span',{class:'overview-title'},head);
const arrow=el('span',{class:'overview-arrow',text:'›','aria-hidden':'true'},head);
const status=el('div',{class:'overview-status','aria-live':'polite'},tile);
const note=el('div',{class:'overview-note'},tile);
let target=0,statusKey='';
function openRoom(){if(!target)return;try{openObject(target)}catch(error){note.textContent='Der Raum konnte nicht geöffnet werden.';note.hidden=false}}
tile.addEventListener('click',openRoom);
tile.addEventListener('keydown',event=>{if((event.key==='Enter'||event.key===' ')&&!event.repeat){event.preventDefault();openRoom()}});
handleMessage=function(data){
 let next;try{next=typeof data==='string'?JSON.parse(data):data}catch(error){return}
 if(!next||!Array.isArray(next.lights))return;
 renderRoom({...next,showTitle:false,showSummary:false});
 const title=String(next.title||'Raum');roomTitle.textContent=title;roomTitle.title=title;
 roomTitle.hidden=next.showTitle===false;
 head.hidden=next.showTitle===false;
 target=Number.isInteger(next.targetCategory)&&next.targetCategory>0?next.targetCategory:0;
 tile.setAttribute('tabindex',target?'0':'-1');tile.setAttribute('aria-disabled',String(!target));tile.setAttribute('aria-label',title+' öffnen');arrow.hidden=!target;
 note.textContent=target?'':'Bitte eine andere Raumkachel-Instanz oder Kategorie als Ziel auswählen.';note.hidden=!!target;
 const stats=[];
 for(const [items,singular,plural] of [[next.lights,'Lichtkreis','Lichtkreisen'],[next.sockets||[],'Schaltkreis','Schaltkreisen']]){
  const active=items.filter(d=>d.enabled);if(!active.length)continue;
  const on=active.filter(d=>d.on===true).length,unknown=active.filter(d=>d.on!==true&&d.on!==false).length;
  const label=active.length===1?singular:plural;
  stats.push({text:`${on} von ${active.length} ${label} ein`+(unknown?` · ${unknown} unbekannt`:''),on:on>0});
 }
 const blinds=(next.blinds||[]).filter(d=>d.enabled);
 if(blinds.length){let open=0,closed=0,part=0,unknown=0;for(const d of blinds){if(d.position===null||!Number.isFinite(d.position))unknown++;else if(d.position<=0)open++;else if(d.position>=100)closed++;else part++}
  const parts=[];if(open)parts.push(open+' offen');if(closed)parts.push(closed+' geschlossen');if(part)parts.push(part+' teilweise');if(unknown)parts.push(unknown+' unbekannt');stats.push({text:'Rollläden: '+parts.join(' · '),on:false});}
 if(!stats.length)stats.push({text:'Keine Statuskreise aktiviert',on:false});
 const key=JSON.stringify(stats);if(key!==statusKey){statusKey=key;status.replaceChildren();for(const s of stats)el('span',{class:'overview-pill'+(s.on?' on':''),text:s.text},status)}
};
