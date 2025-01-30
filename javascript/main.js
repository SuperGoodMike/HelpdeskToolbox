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
                if (this.readyState == 4) {
                    document.getElementById("loading").innerHTML= '';
                    if (this.status == 200) {
                        try {
                            const response = JSON.parse(this.responseText);
                            buildTable(response, callType);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            document.getElementById("txtHint").innerHTML = 
                                '<div class="error">Invalid server response</div>';
                        }
                    } else {
                        console.error('Request failed with status:', this.status);
                        document.getElementById("txtHint").innerHTML = 
                            '<div class="error">Request failed (Status: ' + this.status + ')</div>';
                    }
                }
            }
            document.getElementById("loading").innerHTML = '<div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>'
            const formData = new FormData();
            formData.append('target', domain);
            formData.append('request', callType);
            formData.append('ports', port);
            
            xmlhttp.open("POST", "operations/", true);
            xmlhttp.send(formData);
            
        }
    }

    function buildTable(jsonResp, callType) {
        var requestNum = Date.now();
        if (jsonResp.length == 0) {
            $(".responseTable").prepend("<div class = 'responseRow" + requestNum + "'><table></table></div>");
            $(".responseRow" + requestNum + " Table").append("<tr><td colspan='2' class='thead'>" + requestTitle(callType) + "</td></tr>");
            $(".responseRow" + requestNum + " Table").append("<tr><td colspan='2' style='text-align:center'>NO DATA FOUND</td></tr>");
        } else {

            //creates thes the table to store the response details each table has a unique class
            $(".responseTable").prepend("<div class = 'responseRow" + requestNum + "'><table></table></div>");
            //Creates title bar
            $(".responseRow" + requestNum + " Table").append("<tr><td colspan='2' class='thead'>" + requestTitle(callType) + "</td></tr>");

            for (i = 0, len = jsonResp.length; i < len; i++) {
                var jsonData = jsonResp[i];

                if (i != 0) {$(".responseRow" + (requestNum-1)).append("<Div class = 'responseRow" + requestNum + "'><table></table></div>");}
                //iterates through object keys
                if (callType === "port") {
                    // Create table header
                    $(".responseRow" + requestNum + " table").append(`
                        <tr>
                            <th>Port</th>
                            <th>Status</th>
                            <th>Response Time</th>
                        </tr>
                    `);
                    
                    // Add rows for each port result
                    jsonResp.forEach(result => {
                        const statusClass = result.status === 'open' ? 'status-open' : 'status-closed';
                        $(".responseRow" + requestNum + " table").append(`
                            <tr>
                                <td>${result.port}</td>
                                <td class="${statusClass}">${result.status.toUpperCase()}</td>
                                <td>${formatResponseTime(result.response_time)}</td>
                            </tr>
                        `);
                    });
                } else if (callType === "blacklist") {
                    for (j = 0, len2 = Object.keys(jsonData).length; j < len2; j++) {  
                        $(".responseRow" + requestNum + " table").append("<tr class='twoCol'><td class='left-row'>" + Object.getOwnPropertyNames(jsonData)[j] + ":</td><td>" + jsonData[Object.keys(jsonData)[j]] + "</td></tr>");
                    }
                } else {
                    for (j = 0, len2 = Object.keys(jsonData).length; j < len2; j++) {  
                        $(".responseRow" + requestNum + " table").append("<tr class='twoCol'><td class='left-row'>" + Object.getOwnPropertyNames(jsonData)[j] + ":</td><td>" + cleanString(jsonData[Object.keys(jsonData)[j]].toString()) + "</td></tr>");
                    }
                }
                requestNum++;
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
    const portContainer = document.getElementById("port-container");
    const portInput = document.getElementById("port");
    
    if(document.getElementById("file").value === 'port') {
        portContainer.style.visibility = "visible";
        portInput.placeholder = "Enter ports (e.g., 80, 443, 1-100)";
        portInput.pattern = "^[0-9,\\s-]+$";
        portInput.title = "Enter ports as single, comma-separated, or ranges (e.g., 80,443,8000-8080)";
    } else {
        portContainer.style.visibility = "hidden";
        portInput.value = "";
    }
}

function formatResponseTime(ms) {
    return ms ? `${Math.round(ms * 1000)}ms` : 'N/A';
}
