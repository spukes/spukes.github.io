---
title: "Ver as últimas conexións feitas por RDP a un servidor"
date: 2024-05-01
description: "Tutorial para ver os usuarios que se conectaron por RDP a un servidor Windows usando PowerShell."
prism: true
---

As veces pode ser interesante saber quen se conectou por última vez a un servidor por RDP (Escritorio Remoto) e para iso podemos lanzar este script por Powershell:

```powershell
$RDPAuths = Get-WinEvent -LogName 'Microsoft-Windows-TerminalServices-RemoteConnectionManager/Operational' -FilterXPath '<QueryList><Query Id="0"><Select>*[System[EventID=1149]]</Select></Query></QueryList>'
[xml[]]$xml=$RDPAuths|Foreach{$_.ToXml()}
$EventData = Foreach ($event in $xml.Event)
{ New-Object PSObject -Property @{
TimeCreated = (Get-Date ($event.System.TimeCreated.SystemTime) -Format 'yyyy-MM-dd hh:mm:ss K')
User = $event.UserData.EventXML.Param1
Domain = $event.UserData.EventXML.Param2
Client = $event.UserData.EventXML.Param3
}
} $EventData | FT | select -First 20
```
