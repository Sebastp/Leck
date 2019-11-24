function renderTreeView(){
    var width = 960,
        height = 500;

    var color = d3.scaleOrdinal(d3.schemeCategory20);

    var d3cola = cola.d3adaptor(d3)
        .avoidOverlaps(true)
        .size([width, height]);

    var svg = d3.select("body").append("svg")
        .attr("width", width)
        .attr("height", height);

    d3.json("/graphdata/chris.json", function (error, graph) {
        var nodeRadius = 5;

        graph.nodes.forEach(function (v) { v.height = v.width = 2 * nodeRadius; });

        d3cola
            .nodes(graph.nodes)
            .links(graph.links)
            .flowLayout("y", 30)
            .symmetricDiffLinkLengths(6)
            .start(10,20,20);

        // define arrow markers for graph links
        svg.append('svg:defs').append('svg:marker')
            .attr('id', 'end-arrow')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', 6)
            .attr('markerWidth', 3)
            .attr('markerHeight', 3)
            .attr('orient', 'auto')
          .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr('fill', '#fff');

        var path = svg.selectAll(".link")
            .data(graph.links)
          .enter().append('svg:path')
            .attr('class', 'link');

        var node = svg.selectAll(".node")
            .data(graph.nodes)
          .enter().append("circle")
            .attr("class", "node")
            .attr("r", nodeRadius)
            .style("fill", function (d) { return color(d.group); })
            .call(d3cola.drag);

        node.append("title")
            .text(function (d) { return d.name; });

        d3cola.on("tick", function () {
            path.each(function (d) {
                if (isIE()) this.parentNode.insertBefore(this, this);
            });
            // draw directed edges with proper padding from node centers
            path.attr('d', function (d) {
                var deltaX = d.target.x - d.source.x,
                    deltaY = d.target.y - d.source.y,
                    dist = Math.sqrt(deltaX * deltaX + deltaY * deltaY),
                    normX = deltaX / dist,
                    normY = deltaY / dist,
                    sourcePadding = nodeRadius,
                    targetPadding = nodeRadius + 2,
                    sourceX = d.source.x + (sourcePadding * normX),
                    sourceY = d.source.y + (sourcePadding * normY),
                    targetX = d.target.x - (targetPadding * normX),
                    targetY = d.target.y - (targetPadding * normY);
                return 'M' + sourceX + ',' + sourceY + 'L' + targetX + ',' + targetY;
            });

            node.attr("cx", function (d) { return d.x; })
                .attr("cy", function (d) { return d.y; });
        });
        // turn on overlap avoidance after first convergence
        //cola.on("end", function () {
        //    if (!cola.avoidOverlaps()) {
        //        graph.nodes.forEach(function (v) {
        //            v.width = v.height = 10;
        //        });
        //        cola.avoidOverlaps(true);
        //        cola.start();
        //    }
        //});
    });
}
    function isIE() { return ((navigator.appName == 'Microsoft Internet Explorer') || ((navigator.appName == 'Netscape') && (new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})").exec(navigator.userAgent) != null))); }




function d3TreeDown(){
	var treeData = [
	  {
	    "name": "Top Level",
	    "parent": "null",
	    "children": [
	      {
	        "name": "Level 2: A",
	        "parent": "Top Level",
	        "children": [
	          {
	            "name": "Son of A",
	            "parent": "Level 2: A"
	          },
	          {
	            "name": "Daughter of A",
	            "parent": "Level 2: A"
	          }
	        ]
	      },
	      {
	        "name": "Level 2: B",
	        "parent": "Top Level"
	      }
	    ]
	  }
	];

	// ************** Generate the tree diagram	 *****************
	var margin = {top: 40, right: 120, bottom: 20, left: 120},
		width = 960 - margin.right - margin.left,
		height = 500 - margin.top - margin.bottom;

	var i = 0;

	var tree = d3.tree()
		.size([height, width]);
	var diagonal = d3.svg.diagonal()
		.projection(function(d) { return [d.x, d.y]; });

	var svg = d3.select("body").append("svg")
		.attr("width", width + margin.right + margin.left)
		.attr("height", height + margin.top + margin.bottom)
	  .append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	root = treeData[0];

	update(root);

	function update(source) {

	  // Compute the new tree layout.
	  var nodes = tree.nodes(root).reverse(),
		  links = tree.links(nodes);

	  // Normalize for fixed-depth.
	  nodes.forEach(function(d) { d.y = d.depth * 100; });

	  // Declare the nodes…
	  var node = svg.selectAll("g.node")
		  .data(nodes, function(d) { return d.id || (d.id = ++i); });

	  // Enter the nodes.
	  var nodeEnter = node.enter().append("g")
		  .attr("class", "node")
		  .attr("transform", function(d) {
			  return "translate(" + d.x + "," + d.y + ")"; });

	  nodeEnter.append("circle")
		  .attr("r", 10)
		  .style("fill", "#fff");

	  nodeEnter.append("text")
		  .attr("y", function(d) {
			  return d.children || d._children ? -18 : 18; })
		  .attr("dy", ".35em")
		  .attr("text-anchor", "middle")
		  .text(function(d) { return d.name; })
		  .style("fill-opacity", 1);

	  // Declare the links…
	  var link = svg.selectAll("path.link")
		  .data(links, function(d) { return d.target.id; });

	  // Enter the links.
	  link.enter().insert("path", "g")
		  .attr("class", "link")
		  .attr("d", diagonal);

	}

}




// document.getElementsByClassName('w-tree-icon')[0].addEventListener('click', function(){
 // renderTreeView();
 //d3TreeDown();
// });
