var v=Object.defineProperty;var x=Object.getOwnPropertySymbols;var N=Object.prototype.hasOwnProperty,k=Object.prototype.propertyIsEnumerable;var b=(a,s,t)=>s in a?v(a,s,{enumerable:!0,configurable:!0,writable:!0,value:t}):a[s]=t,j=(a,s)=>{for(var t in s||(s={}))N.call(s,t)&&b(a,t,s[t]);if(x)for(var t of x(s))k.call(s,t)&&b(a,t,s[t]);return a};import{r as m,j as n}from"./index-1.0.3.js";import{u as M,b as _,a as z,_ as l}from"./bi-895-8.js";import{Z as E,I as Z,s as L}from"./bi-700-15.js";import{h as C,c as P}from"./bi-382-16.js";function q({formFields:a,setIntegration:s,integrations:t,allIntegURL:p}){const f=M(),{id:h,formID:r}=_(),[e,d]=m.useState(j({},t[h])),[I,g]=m.useState(!1),[w,i]=m.useState({show:!1}),[u,y]=m.useState(0),S=()=>{if(!P(e)){i({show:!0,msg:l("Please map mandatory fields","bitgfzc")});return}L(r,t,s,p,e,f,h,1).then(o=>{o.success?(i({show:!0,msg:o==null?void 0:o.data}),setTimeout(()=>{f.push(p)},200)):i({show:!0,msg:(o==null?void 0:o.data)||o})})};return n.jsxs("div",{style:{width:900},children:[n.jsx(z,{snack:w,setSnackbar:i}),n.jsxs("div",{className:"flx mt-3",children:[n.jsx("b",{className:"wdt-100 d-in-b",children:l("Integration Name:","bitgfzc")}),n.jsx("input",{className:"btcd-paper-inp w-7",onChange:c=>C(c,u,e,d),name:"name",value:e.name,type:"text",placeholder:l("Integration Name...","bitgfzc")})]}),n.jsx(E,{tab:u,settab:y,formID:r,formFields:a,handleInput:c=>C(c,u,e,d,r,g,i),crmConf:e,setCrmConf:d,isLoading:I,setisLoading:g,setSnackbar:i}),n.jsx(Z,{edit:!0,saveConfig:S,disabled:e.module===""||e.layout===""||e.field_map.length<1}),n.jsx("br",{})]})}export{q as default};
