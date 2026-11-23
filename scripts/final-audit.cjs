/* Repeatable, non-destructive browser integration audit. Creates labeled demo data.
   PLAYWRIGHT_PATH must point to an installed Playwright module; no production dependency added. */
const {chromium} = require(process.env.PLAYWRIGHT_PATH || 'playwright');
const fs = require('fs');
const assert = require('assert/strict');
const crypto = require('crypto');
const origin = process.env.AUDIT_ORIGIN || 'https://localhost:8443';
const base = origin + '/blog/index.php/';
const fixturePath = process.env.AUDIT_FIXTURES || '/tmp/moonio-final-fixtures.json';
const results = [];
function pass(name) { results.push(name); console.log('PASS ' + name); }
async function api(page, path, data) {
 return page.evaluate(async ({path,data}) => { const r=await fetch(path, data ? {method:'POST',body:new URLSearchParams(data)} : {});const text=await r.text();let body;try{body=JSON.parse(text)}catch{} return {status:r.status,body,text}; },{path:base+path,data});
}
(async()=>{
 const browser=await chromium.launch({executablePath:process.env.CHROME_PATH || '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',headless:true}); // Browser origin security is NOT bypassed.
 const pages=[], contexts=[], errors=[];
 try {
 let fixtures;
 if(fs.existsSync(fixturePath)) fixtures=JSON.parse(fs.readFileSync(fixturePath));
 else {const stamp=Date.now();fixtures={password:crypto.randomBytes(18).toString('base64')+'Aa1!',users:['Alpha','Beta','Gamma','Delta'].map((name,i)=>({name,email:`finalaudit${stamp}${i}@example.com`}))};fs.writeFileSync(fixturePath,JSON.stringify(fixtures),{mode:0o600});}
 for (const u of fixtures.users) {
   const context=await browser.newContext({permissions:['camera','microphone','geolocation'],geolocation:{latitude:25.6866,longitude:-100.3161}});
   contexts.push(context);const page=await context.newPage();pages.push(page);
   page.on('pageerror', e=>errors.push(e.message));
   page.on('dialog', dialog=>dialog.accept());
   await page.goto(base+'login');
   if (!u.id) {
    await page.goto(base+'employee/add');
    for(const [key,value] of Object.entries({first_name:u.name,last_name:'Finalaudit',email:u.email,password:fixtures.password,birth_date:'2000-01-01',phone:'8112345678'})) await page.locator(`[name="${key}"]`).fill(value);
    await Promise.all([page.waitForURL('**/login'),page.locator('button[type=submit]').click()]);
   }
   await page.locator('[name=email]').fill(u.email); await page.locator('[name=password]').fill(fixtures.password);
   await Promise.all([page.waitForURL('**/home'),page.locator('button[type=submit]').click()]);
   await page.goto(base+'chats'); await page.waitForFunction(()=>socket.connected);
   const session=await api(page,'api/socket_session');assert.equal(session.status,200);u.id=session.body.id;
   await page.evaluate(()=>{window.auditMessages=[];socket.on('receive_message',m=>window.auditMessages.push(m));});
 }
 fs.writeFileSync(fixturePath,JSON.stringify(fixtures),{mode:0o600});
 const [a,b,c,d]=pages;const [A,B,C,D]=fixtures.users;
 pass('Registration without photo, password login, automatic Socket.IO connection: four independent sessions');
 assert.equal(await a.evaluate(()=>window.isSecureContext),true);
 assert.equal(await a.evaluate(()=>socket.io.engine.transport.name),'websocket');
 pass('Trusted HTTPS + secure browser context + Apache WebSocket upgrade');
 const duplicate=await api(a,'employee/store',{first_name:A.name,last_name:'Finalaudit',email:A.email,password:fixtures.password,birth_date:'2000-01-01',phone:'8112345678'});
 assert.match(duplicate.text,/unique|únic|exist/i);
 const invalid=await api(a,'employee/store',{first_name:A.name,last_name:'Finalaudit',email:'invalid',password:'bad',birth_date:'2000-01-01',phone:'123'});
 assert.match(invalid.text,/valid|válid/i);pass('Duplicate and invalid email/password rejected');
 const create=async (page,type,name,ids)=>page.evaluate(async ({url,type,name,ids})=>{const form=new FormData();form.append('type',type);form.append('name',name);ids.forEach(id=>form.append('participants[]',id));const r=await fetch(url,{method:'POST',body:form});return {status:r.status,body:await r.json()};},{url:base+'api/create_chat',type,name,ids});
 assert.equal((await create(a,'grupo','Too small',[B.id])).status,400);
 assert.equal((await create(a,'grupo','',[B.id,C.id])).status,400);
 // Exercise the actual Nueva conversación interface for a valid three-user group.
 await a.getByRole('button',{name:'Nueva conversación',exact:true}).first().click();
 await a.locator(`#usersList input[value="${B.id}"]`).check();await a.locator(`#usersList input[value="${C.id}"]`).check();
 const groupName='Final audit '+Date.now();await a.locator('#groupName').fill(groupName);
 await a.locator('#groupModal').getByRole('button',{name:'Nueva conversación',exact:true}).click();
 await b.locator('#chatList button').filter({hasText:groupName}).waitFor();await c.locator('#chatList button').filter({hasText:groupName}).waitFor();
 const chats=(await api(a,'api/user_chats/'+A.id)).body;const group=Number(chats.find(x=>x.name===groupName).id_chats);
 const priv=(await create(a,'privado','',[B.id])).body.chat_id;
 fixtures.group=Number(group);fixtures.private=Number(priv);fs.writeFileSync(fixturePath,JSON.stringify(fixtures),{mode:0o600});
 pass('UI creates named group of three including creator; live discovery by B/C; invalid groups rejected');
 const open=async(p,id,peer=null)=>p.evaluate(({id,peer})=>openChat(Number(id),'Audit','',peer),{id,peer});
 const send=async(p,message,encrypted=false)=>p.evaluate(async ({message,encrypted})=>{document.getElementById('encryptMessage').checked=encrypted;return sendText(message);},{message,encrypted});
 await open(a,priv,B.id);await open(b,priv,A.id);
 await send(a,'private A to B');await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('private A to B'));
 await send(b,'private B to A');await a.waitForFunction(()=>document.getElementById('chat').textContent.includes('private B to A'));
 assert.equal(await c.evaluate(()=>auditMessages.some(m=>m.message==='private A to B')),false);
 assert.equal((await api(c,'api/messages/'+priv)).status,403);
 pass('Private bidirectional real-time delivery and outsider history/notification isolation');
 await send(a,'encrypted audit secret',true);await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('encrypted audit secret'));
 assert.match(await b.locator('#chat').innerText(),/Cifrado/);
 await b.reload();await b.waitForFunction(()=>socket.connected);await open(b,priv,A.id);await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('encrypted audit secret'));
 pass('Encryption ON/OFF and transparent decryption in real-time/history after refresh');
 await open(a,group);await open(b,group);await open(c,group);
 await send(a,'group A to BC');await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('group A to BC'));await c.waitForFunction(()=>document.getElementById('chat').textContent.includes('group A to BC'));
 await send(b,'group B to AC');await a.waitForFunction(()=>document.getElementById('chat').textContent.includes('group B to AC'));await c.waitForFunction(()=>document.getElementById('chat').textContent.includes('group B to AC'));
 assert.equal(await d.evaluate(()=>auditMessages.length),0);pass('Three-user group real-time broadcast; unrelated fourth user receives nothing');
 await open(a,priv,B.id);await send(b,'inactive group notification');await a.waitForFunction(id=>document.querySelector(`[data-chat-id="${id}"] .unread-badge`),group);
 await open(a,group);await a.waitForFunction(id=>!document.querySelector(`[data-chat-id="${id}"] .unread-badge`),group);pass('Inactive chat unread badge appears and clears on opening');
 const btab=await contexts[1].newPage();await btab.goto(base+'chats');await btab.waitForFunction(()=>socket.connected);
 await b.evaluate(()=>socket.disconnect());await a.waitForFunction(id=>onlineUsers.has(id),B.id);
 await btab.close();await a.waitForFunction(id=>!onlineUsers.has(id),B.id);pass('Presence remains online with another tab and goes offline when last socket closes');
 await open(a,priv,B.id);await send(a,'offline pending audit');await b.evaluate(()=>socket.connect());await b.waitForFunction(()=>socket.connected);await a.waitForFunction(id=>onlineUsers.has(id),B.id);
 await b.waitForFunction(id=>document.querySelector(`[data-chat-id="${id}"] .unread-badge`),Number(priv));await open(b,priv,A.id);await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('offline pending audit'));
 pass('Offline recipient gets persisted pending badge on reconnect and message on opening');
 await contexts[1].setOffline(true);await b.evaluate(()=>socket.io.engine.close());await b.waitForFunction(()=>!socket.connected);await send(a,'during network outage');await contexts[1].setOffline(false);await b.waitForFunction(()=>socket.connected);await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('during network outage'));
 assert.equal(await b.locator('#chat .chat-message').count(),(await api(b,'api/messages/'+priv)).body.length);pass('Temporary network loss: auto-reconnect, re-register, rejoin, reload history without duplicates');
 await b.goto(base+'employee/logout');await b.goto(base+'login');await b.locator('[name=email]').fill(B.email);await b.locator('[name=password]').fill(fixtures.password);await Promise.all([b.waitForURL('**/home'),b.locator('button[type=submit]').click()]);await b.goto(base+'chats');await b.waitForFunction(()=>socket.connected);await open(b,priv,A.id);await b.waitForFunction(()=>document.getElementById('chat').textContent.includes('during network outage'));pass('History survives logout/login');
 await open(a,priv,B.id);await open(b,priv,A.id);
 await a.evaluate(()=>shareLocation());await b.waitForFunction(()=>document.querySelector('#chat a[href^="https://www.google.com/maps?q="]'));pass('Granted geolocation produces clickable persisted Maps link (synthetic coordinates)');
 // File payloads are real MIME-checked txt, png and wav; no external file download.
 const files=[{name:'audit.txt',mimeType:'text/plain',buffer:Buffer.from('Moonio file audit')},{name:'audit.png',mimeType:'image/png',buffer:Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aNn8AAAAASUVORK5CYII=','base64')}];
 const wav=Buffer.alloc(844);wav.write('RIFF');wav.writeUInt32LE(836,4);wav.write('WAVEfmt ',8);wav.writeUInt32LE(16,16);wav.writeUInt16LE(1,20);wav.writeUInt16LE(1,22);wav.writeUInt32LE(8000,24);wav.writeUInt32LE(16000,28);wav.writeUInt16LE(2,32);wav.writeUInt16LE(16,34);wav.write('data',36);wav.writeUInt32LE(800,40);files.push({name:'audit.wav',mimeType:'audio/wav',buffer:wav});
 for(const file of files){await a.locator('#chatFile').setInputFiles(file);await a.waitForFunction(()=>document.getElementById('chatStatus').textContent==='Archivo compartido y mensaje guardado');await b.waitForFunction(name=>document.getElementById('chat').textContent.includes(name),file.name);const href=await b.locator('#chat .chat-message').filter({hasText:file.name}).last().locator('a').getAttribute('href');const response=await b.evaluate(async url=>{const r=await fetch(url);return {status:r.status,bytes:Array.from(new Uint8Array(await r.arrayBuffer()))};},origin+href);assert.equal(response.status,200);assert.deepEqual(Buffer.from(response.bytes),file.buffer);}
 pass('Text/image/audio uploads, persisted chat links, exact recipient download bytes');
 await a.locator('#chatFile').setInputFiles({name:'large.txt',mimeType:'text/plain',buffer:Buffer.alloc(21*1024*1024)});await a.waitForFunction(()=>document.getElementById('chatStatus').textContent==='Máximo 20 MB.');assert.equal(await a.evaluate(async url=>{const data=new FormData();data.append('chat_upload','1');data.append('userfile',new Blob([new Uint8Array(21*1024*1024)],{type:'text/plain'}),'large.txt');return (await fetch(url,{method:'POST',body:data})).status;},base+'ftp/upload'),400);pass('20 MB upload limit enforced by browser and backend');
 // Native getUserMedia is a physical-device check. Inject generated media ONLY in these test pages.
 // The application's offer/answer/ICE, RTCPeerConnection, rendering and cleanup run unchanged.
 for (const p of [a,b]) await p.evaluate(()=>{
   navigator.mediaDevices.getUserMedia = async () => {
     const canvas=document.createElement('canvas');canvas.width=160;canvas.height=120;
     const ctx=canvas.getContext('2d');let frame=0;const timer=setInterval(()=>{ctx.fillStyle=frame++%2 ? 'purple':'blue';ctx.fillRect(0,0,160,120);},50);
     const video=canvas.captureStream(20);video.getVideoTracks()[0].addEventListener('ended',()=>clearInterval(timer));
     const audio=new AudioContext();const dest=audio.createMediaStreamDestination();const oscillator=audio.createOscillator();oscillator.connect(dest);oscillator.start();
     return new MediaStream([...video.getTracks(),...dest.stream.getTracks()]);
   };
 });
 await a.evaluate(()=>startCall());await a.waitForFunction(()=>peerConnection?.connectionState==='connected',{},{timeout:20000});await b.waitForFunction(()=>peerConnection?.connectionState==='connected',{},{timeout:20000});
 for(const p of [a,b])assert.equal(await p.evaluate(()=>document.getElementById('remoteVideo').srcObject.getTracks().length),2);
 await a.getByRole('button',{name:'Colgar',exact:true}).click();await b.waitForFunction(()=>peerConnection===null);assert.equal(await a.evaluate(()=>localStream.getTracks().every(t=>t.readyState==='ended')),true);pass('WebRTC offer/answer/ICE connects with remote audio+video tracks and hang-up cleanup (synthetic media)');
 await a.goto(base+'tasks/create');await a.locator('#taskGroup').selectOption(String(group));const memberIds=await a.locator('#taskAssignee option').evaluateAll(nodes=>nodes.map(n=>n.value).filter(Boolean));assert.deepEqual(memberIds.map(Number).sort((a,b)=>a-b),[A.id,B.id,C.id].sort((a,b)=>a-b));await a.locator('#taskAssignee').selectOption(String(B.id));const title='Final audit task '+Date.now();await a.locator('[name=title]').fill(title);await Promise.all([a.waitForURL('**/task_manager'),a.locator('button[type=submit]').click()]);
  const taskId=await a.locator('a[href*="tasks/advance/"]').evaluateAll((links,title)=>{const link=links.find(l=>l.parentElement.parentElement.textContent.includes(title));return link?.href.match(/advance\/(\d+)/)[1];},title);
 assert.ok(taskId);await a.goto(base+'tasks/advance/'+taskId);await a.goto(base+'tasks/advance/'+taskId);fixtures.task=Number(taskId);fs.writeFileSync(fixturePath,JSON.stringify(fixtures),{mode:0o600});
 assert.equal((await api(a,'tasks/store',{id_chats:group,assigned_to:D.id,title:'invalid',status:'bench',priority:'regular'})).status,400);pass('Group task UI restricts assignee to members; create and advance twice; outsider assignment rejected');
 for(const p of [a,b]){await p.goto(base+'rewards');await p.getByText('Clasificación de todos los usuarios',{exact:true}).waitFor();assert.match(await p.locator('body').innerText(),/Alpha Finalaudit/);}pass('Rewards and shared ranking visible from two independent user accounts');
 await a.goto(base+'smtp');assert.ok(await a.locator('select[name=to] option').count()>1);const rejectedEmail=await api(a,'smtp/send',{to:'notregistered@example.com',subject:'audit',message:'audit'});assert.equal(rejectedEmail.status,200);assert.match(rejectedEmail.text,/moon-feedback-error/);pass('Email selector lists registered users; arbitrary recipient rejected (no external email sent)');
 assert.deepEqual(errors,[]);pass('No browser JavaScript exceptions');
 console.log(JSON.stringify({passed:results.length,group:fixtures.group,private:fixtures.private,task:fixtures.task,users:fixtures.users.map(u=>u.id)},null,2));
 }finally{await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1});
