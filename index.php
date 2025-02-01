<?php
header("Cache-control: no-cache, max-age=0");
header("Expires: 0");
header("Expires: Tue, 01 Jan 1980 1:00:00 GMT");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Helpdesk.ca toolbox is a Javascript and PHP app to look up DNS records such as SPF, MX, Whois, and more">
    <meta property="og:description" content="Helpdesk.ca toolbo is a Javascript and PHP app to look up DNS records such as SPF, MX, Whois, and more" >
    <meta name="keywords" content="MXToolbox, DNS, Blacklist, MX, PHP">
    <meta name="author" content="Helpdesk.ca">

    <title> Helpdesk Toolbox </title>
    <meta name="msapplication-TileColor" content="#f04444">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Helpdesk Toolbox">
    <meta name="theme-color" content="#f04444">

    <link rel="stylesheet" href="libraries/bootstrap/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="styles/style.css" rel="stylesheet">

</head>
<body>
    <a href="https://helpdesk.ca/contact" class="github-corner" aria-label="View source on Github"><svg width="80" height="80" viewBox="0 0 250 250" style="fill:#44c0f0; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true">
        <!-- GitHub SVG content -->
    </svg></a>
    <div class="container">
        <div class="row" id="top-row">
            <div class="col-md-12">
                <center><H1 class="logo"><Span class="logo-style1">Helpdesk</Span>Toolbox</H1></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="domain-container">
                    <br/>
                    <br/>
                    <span class="form-label">Domain:&nbsp;</span>
                    <input type="text" name="domain" id="domain" class="form-control">
                    <select onchange="showAdditionalFields()" id="file" class="form-control">
                        <option value="a">IP/Get A Record</option>
                        <option value="aaaa">IPV6/Get AAAA Record</option>
                        <option value="mx">Mx/Get MX Record</option>
                        <option value="txt">SPF/TXT</option>
                        <option value="dmarc">DMARC</option>
                        <option value="blacklist">Blacklist Check</option>
                        <option value="whois">Whois</option>
                        <option value="port">Check Ports: Open/Closed</option>
                        <option value="hinfo">Hinfo/Get Hardware Information</option>
                        <option value="all">Get All Simple DNS Records</option>
                        <option value="reverseLookup">Host By IP/Reverse Lookup</option>
                    </select>
                </div>
                <div style="visibility: hidden" id="port-container">
                    <span class="form-label">Port/Ports:&nbsp;</span><input type="text" name="port" id="port" class="form-control">
                    <span class="form-label">Protocol:&nbsp;</span>
                    <select id="protocol" name="protocol" class="form-control">
                        <option value="tcp">TCP</option>
                        <option value="udp">UDP</option>
                    </select>
                </div>
                <div id="submit-container-center">
                    <input type="button" id="submit" value="submit" class="form-control2 btn"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <span id="txtHint" style="color: red;"></span>
                <div id="loading">
                <div class="info">
                <br/>
                <br/>
                <br/>
                    <table>
                        <tr>
                            <th>Query</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <th>IP/Get A Record</th>
                            <td>An A Record is used to associate a domain name with an IP(v4) address. This query checks for the A records set on the domain</td>
                        </tr>
                        <tr>
                            <th>IPV6/Get AAAA Record</th>
                            <td>An AAAA Record is used to associate a domain name with an IP(v6) address. This query checks for the AAAA records set on the domain</td>
                        </tr>
                        <tr>
                            <th>Mx/Get MX Record</th>
                            <td>MX stands for Mail Exchanger.  This query is used to get the mail server used for accepting emails on the specified domain.</td>
                        </tr>
                        <tr>
                            <th>SPF/TXT</th>
                            <td>A SPF Record is used to indicate which email hosts is authorized to send mail on the specified domain's behalf.  This query is used to get the authorized domains</td>
                        </tr>
                        <tr>
                            <th>DMARC</th>
                            <td>A DMARC Record is used to authenticate email From: addresses and defines policies on where to report both authorized and unauthorized mailflow</td>
                        </tr>
                        <tr>
                            <th>Blacklist Check</th>
                            <td>This query is used to check if the specified domain is on any of the most well known email blacklist sites.  If a domain is on a blacklist the row will return a failed status</td>
                        </tr>
                        <tr>
                            <th>Whois</td>
                            <td>This information gets whois information to see who possibly owns the domain.</td>
                        </tr>
                        <tr>
                            <th>Check Ports: Open/Closed</th>
                            <td>You are able to check if a port (22), ports (22,23,80), or a range (22-25) on a domain or IP address are open or closed</td>
                        </tr>
                        <tr>
                            <th>Hinfo/Get Hardware Information</th>
                            <td>If available, this query gets the hardware information of the server for the specified hostname</td>
                        </tr>
                        <tr>
                            <th>Get All Simple DNS Records</th>
                            <td>This query attemps to do a request for all of the available DNS information for the specified hostname.  This is not always successfull as some providers block the queries</td>
                        </tr>
                        <tr>
                            <th>Host By IP/Reverse Lookup</th>
                            <td>The query attempts to find a hostname associated with an IP address</td>
                        </tr>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="responseArea" class="col-md-12">
                    <div  class="responseTable">

                    </div>
                </div>
                <footer>
                    <div class="row text-center">
                        <div class="col-md-12">
                            <p>Hosted by <a href="https://helpdesk.ca">Helpdesk.ca</a> | 2025</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>        
    </div>
    <script src="libraries/jquery/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="libraries/popper/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="libraries/bootstrap/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src ="javascript/main.js"></script>
    <script>
        function showAdditionalFields() {
            var selectBox = document.getElementById('file');
            var portContainer = document.getElementById('port-container');
            if (selectBox.value == 'port') {
                portContainer.style.visibility = 'visible';
            } else {
                portContainer.style.visibility = 'hidden';
            }
        }
    </script>
</body>
</html>
