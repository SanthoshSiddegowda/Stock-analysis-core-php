function readSingleFile(e) {
    var file = e.target.files[0];
    if (!file) {
        return;
    }

    var reader = new FileReader();
    reader.onload = function(e) {
        var contents = e.target.result;
        displayContents(contents);
    };

    reader.readAsText(file);
}

function displayContents(contents) {
    var csv = contents;
    var data = d3.csvParse(csv);
    var nest = d3.nest()
        .key(function(row) {
            return row.stock_name;
        })
        .rollup(function(values) {
            return values;
        })
        .entries(data);

    if (nest[0].key == 'undefined') {
        clearOptions('stock_name');

        alert('Invalid CSV File Uploaded');
    } else {

        clearOptions('stock_name');

		var items = [];
		var elems = [];
		
        nest.forEach(function(item, index) {
            var option = document.createElement("option");
            option.text = item.key;
            option.value = item.key;
            var select = document.getElementById("stock_name");
            select.appendChild(option);
        });
		
    }


	document.getElementById('csv_data').value = (JSON.stringify(nest));
 	
	var test = document.getElementById('csv_data').value;
	
	document.getElementById('csv_data').value = JSON.parse(nest);
}

function clearOptions(id) {

    document.getElementById(id).innerText = '';

    var option = document.createElement("option");
    option.text = "Select Stock  Name"
    option.value = "";
    var select = document.getElementById(id);
    select.appendChild(option);

}

document.getElementById('file').addEventListener('change', readSingleFile, false);

$(function() {

    date = new Date();
    nextDate = date.setDate(date.getDate() + 1);

    $("#start_date").datepicker({
        autoclose: true,
        todayHighlight: true
    }).datepicker('update', new Date());

    $("#end_date").datepicker({
        autoclose: true,
        todayHighlight: true
    }).datepicker('update', date);

});
