/**
 * Variables CREATURE_* should come from php script.
 * 
 * This file uses:
 * - CREATURE_NAME : string
 * - CREATURE_HP : int
 */

var ID = 0;

function startTracking() {
    let tracking = document.getElementById("creature-tracking");

    // Create table
    let table = document.createElement("table");
    table.id = "tracking-table";

    // Create header and header row
    let header = document.createElement("thead");
    table.appendChild(header);
    let headerRow = document.createElement("tr");
    header.appendChild(headerRow);
    
    // Create name header column
    {
        let column = document.createElement("td");
        column.textContent = "Name";
        headerRow.appendChild(column);
    }

    // Create hp header column
    {
        let column = document.createElement("td");
        column.textContent = "HP";
        headerRow.appendChild(column);
    }

    // Create remove button header column
    {
        let column = document.createElement("td");
        column.textContent = "";
        headerRow.appendChild(column);
    }

    // Create table body
    let body = document.createElement("tbody");
    body.id = "tracking-table-body";
    table.appendChild(body);

    // Add to document
    tracking.insertAdjacentElement('beforeend', table);

    // Add the first row
    addTracker();

    // Update button
    let trackButton = document.getElementById("track-button");
    trackButton.setAttribute("onclick", "addTracker()");
    trackButton.textContent = "Track another creature";
}

/**
 * Adds a div with elements to track one creature
 */
function addTracker() {
    ID += 1;

    let tbody = document.getElementById("tracking-table-body");

    let row = document.createElement("tr");

    // Name input
    {
        let td = document.createElement("td");
        row.appendChild(td);

        let name = document.createElement("input");
        name.type = "text";
        name.value = CREATURE_NAME + " " + ID;
        td.appendChild(name);
    }

    // Health input
    {
        let td = document.createElement("td");
        row.appendChild(td);

        let health = document.createElement("input");
        health.type = "number";
        health.value = CREATURE_HP;
        td.appendChild(health);
    }

    // Remove button
    {
        let td = document.createElement("td");
        row.appendChild(td);

        let remove = document.createElement("button");
        remove.textContent = "X";
        remove.addEventListener("click", () => { row.remove(); });
        td.appendChild(remove);
    }

    // Add to document
    tbody.insertAdjacentElement('beforeend', row);
}