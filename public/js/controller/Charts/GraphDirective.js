
/*
 * Copyright 2015 be.wan s.p.r.l.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 */
 
/*
 * @author Ahmed Zarioh (ahmed.zarioh@gmail.com)
 */

/* Directive */
crudControllers.directive('ngGraph', function($interval)
{
    return{
        restrict: 'E',
		/*scope :{
			data : "=",
			change : "&"
		},*/
        link: function(scope, elem, attrs)
		{
            var graph = null;   
            var flotOptions = {
				series: { lines: { show: true }, points: { show: true } },
				grid: { hoverable: true },
				selection: { mode: "xy" },
				xaxis: { mode: "time", timezone: "browser" },
				colors: ["#37ABC8","#006680"]
			};
			
            // If the data changes somehow, update it in the chart
            scope.$watch('data', function(v)
			{	
				if(v)
				{
					if(!graph)
					{	
						graph = $.plot(elem, v , flotOptions);
						elem.show();	
						elem.UseTooltip();
					}
					else
					{
						graph.setData(v);
						graph.setupGrid();
						graph.draw();
					}
				}
            });
        }
    };
});