$(document).ready(function(){
    $("#domain").keyup(function(event){
        if(event.keyCode == 13){
            $("#submit").click();
        }
    });
});
window.onload = function() {
//Counts the number of requests in this session
    var requestNum = 0;
    //Choose the correct script to run based on dropdown selection
    document.getElementById("submit").onclick = function callRoute() {
            returnDnsDetails(document.getElementById("domain").value, document.getElementById("file").value, document.getElementById("port").value)
    }

    function requestTitle(callType){
        switch(callType){
            case "txt":
                return "SPF/TXT Lookup";
                break;
            case "mx":
                return "MX Lookup";
                break;
            case "dmarc":
                return "DMARC";
                break;
            case "a":
                return "IP Lookup";
                break;
            case "all":
                return "All available DNS records";
                break;
            case "aaaa":
                return "IPV6 Lookup";
                break;
            case "whois":
                return "Who Is Lookup";
                break;
            case "hinfo":
                return "H Info Lookup";
                break;
            case "blacklist":
                return "Blacklist Lookup";
                break;
            case "port":
                return "Ports Lookup";
                break;
            case "reverseLookup":
                return "Host Lookup";
                break;
        }
    }

    //Get DNS Details
    function returnDnsDetails(domain, callType, port) {
        //checks for valid input
        if (domain.length == 0) {
            document.getElementById("txtHint").innerHTML = " Please enter a valid domain";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            
            xmlhttp.onreadystatechange = function () {
                var date = new Date();
                if (this.readyState == 4 && this.status == 200) {
                    //Clears the hint field
                    document.getElementById("txtHint").innerHTML = "";
                    document.getElementById("loading").innerHTML= '';
                    //parse the response into a JS Object
                    dnsResp = JSON.parse(this.responseText);        
                    buildTable(dnsResp, callType);
                }
            }
            document.getElementById("loading").innerHTML = '<div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>'
            xmlhttp.open("GET", "operations/?domain=" + domain + "&request=" + callType + "&port=" + port, true);
            xmlhttp.send();
            
        }
    }

function buildTable(jsonResp, callType) {
    var requestNum = Date.now();
    if (jsonResp.length == 0) {
        $(".responseTable").prepend("<div class = 'responseRow" + requestNum + "'><table></table></div>");
        $(".responseRow" + requestNum + " Table").append("<tr><td colspan='2' class='thead'>" + requestTitle(callType) + "</td></tr>");
        $(".responseRow" + requestNum + " Table").append("<tr><td colspan='2' style='text-align:center'>NO DATA FOUND</td></tr>");
    } else {
        $(".responseTable").prepend("<div class = 'responseRow" + requestNum + "'><table></table></div>");
        $(".responseRow" + requestNum + " Table").append("<tr><td colspan='2' class='thead'>" + requestTitle(callType) + "</td></tr>");

        for (var i = 0; i < jsonResp.length; i++) {
            var jsonData = jsonResp[i];
            for (var key in jsonData) {
                if (jsonData.hasOwnProperty(key)) {
                    $(".responseRow" + requestNum + " Table").append("<tr class='twoCol'><td class='left-row'>" + key + ":</td><td>" + jsonData[key] + "</td></tr>");
                }
            }
        }
    }
}
    function cleanString(data) {
        return data
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
     }

}

function showAdditionalFields() {
    if(document.getElementById("file").value === 'port') {
        document.getElementById("port-container").style.visibility="visible" ;   
    } else {
        document.getElementById("port-container").style.visibility="hidden";  
    }
}
