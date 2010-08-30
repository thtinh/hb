function getObject(obj) {
var strObj;
if (document.all) {
  strObj = document.all.item(obj);
} else if (document.getElementById) {
  strObj = document.getElementById(obj);
}
return strObj;
}

function insertRow() {
var oTable = getObject("valueslist");
var oRow, oCell ,oCellCont, oInput;

// Create and insert rows and cells into the first body.
oRow = document.createElement("TR");
oTable.appendChild(oRow);

oCell = document.createElement("TD");
oCell.colSpan="2";
oCell.innerHTML = "&nbsp;";
oRow.appendChild(oCell);

oCell = document.createElement("TD");
oInput=document.createElement("INPUT");
oFieldNameInput = oInput;
oInput.name="value_name[]";
oInput.type="text";
oInput.value="";
oCell.appendChild(oInput);
oRow.appendChild(oCell);

oCell = document.createElement("TD");
oInput=document.createElement("INPUT");
oInput.name="value_label[]";
oInput.type="text";
oInput.value="";
oCell.appendChild(oInput);
oRow.appendChild(oCell);

oCell = document.createElement("TD");
oCell.colSpan="6";
oInput=document.createElement("INPUT");
oInput.name="value_id[]";
oInput.type="hidden";
oInput.value="0";
oCell.appendChild(oInput);
oRow.appendChild(oCell);

oCell = document.createElement("TD");
oInput=document.createElement("INPUT");
oInput.type="button";
oInput.value="x";
oInput.onclick=function(){oTable.removeChild(this.parentNode.parentNode)};
oCell.appendChild(oInput);
oRow.appendChild(oCell);

oFieldNameInput.focus();

}
