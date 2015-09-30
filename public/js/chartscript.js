function getInfoChart( queries) 
{ 
	var data = [];
	if (queries.length == 0) 
	{
		return;
	}
	var dataPointCount = 0;
	var axisCount = 0;
	var metricCount = 0;
	queries.forEach(function (resultSet) 
	{
		var axis = {};
		if (metricCount == 0) {
			axisCount++;
		}
		else if ((metricData != null) && (metricData[metricCount].scale)) {
			axis.position = 'right';
			axisCount++;
		}
		resultSet.results.forEach(function (queryResult) 
		{

			var groupByMessage = "";
			var result = {};
			result.name = queryResult.name;
			result.label = queryResult.name;
			result.data = queryResult.values;
			result.yaxis = axisCount;

			dataPointCount += queryResult.values.length;
			data.push(result);
		});
		metricCount++;
	});
	var $status = $('#status');

	return data;
}


$.fn.UseTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {                         
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
 
                $("#tooltip").remove();
                 
                var y = item.datapoint[1];      

				var date = new Date(item.datapoint[0]);
				formatedDate = date.toLocaleString();



                showTooltip(item.pageX, item.pageY,  item.series.label + "<br/>" + formatedDate + "<br/>" +"<strong>" + y + "</strong>");
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 20,
        border: '2px solid #4572A7',
        padding: '2px',     
        size: '10',   
        'border-radius': '6px 6px 6px 6px',
        'background-color': '#fff',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);
}
