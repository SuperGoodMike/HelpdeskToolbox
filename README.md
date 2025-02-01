Originally Created by **[Charles Barnes](http://charlesabarnes.com)**

# HelpdeskToolbox
## This web application can check simple information such as the following
- IP/Get A Records
- IPV6/Get AAAA Records
- Mx/Get MX Records
- SPF/TXT Records
- DMARC
- Blacklist Check
- Whois
- Get Hardware Information
- Get All Simple DNS Records
- Check Ports: Open/Closed
  - Single (22)
  -  Multiple (22,443,80)
  -  Range (1-1024)
  -  Choose TCP or UDP

## Install manually

- On your PHP webserver git clone the repo
- Extract the contents of the zip file to your desired directory.
- Create a user for login
``` bash
htpasswd -B /etc/secure/toolbox/.htpasswd admin
```
- Navigate to index.php
- Enter the domain you want info about and submit!
