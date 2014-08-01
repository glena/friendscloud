var force,svg, currentData = null;

function initCloud() {
    svg = d3.select("body").append("svg")
        .attr("width", 900)
        .attr("height", 900);

    force = d3.layout.force()
        .size([900, 900]);
}

function loadFriends(next_cursor_str)
{
    var requestData = {};

    if (next_cursor_str)
    {
        requestData.next_cursor_str = next_cursor_str;
    }

    $.ajax({
        url: "twitter/friends",
        data: requestData
    })
    .done()
        .then(remapUserData)
        .then(appendData)
        //.then(loadCloud)
        .then(function(next_cursor_str){
            if (next_cursor_str !== '0')
            {
                setTimeout(function(){
                    loadFriends(next_cursor_str)
                }, 500);
            }
            else
            {
                loadCloud(currentData);
            }
        });
}

function loadFollowers()
{
    $.ajax({
        url: "twitter/followers"
    })
    .done(function( data ) {
        console.log(data);
    });
}

function appendData(data)
{
    if (currentData === null)
    {
        currentData = data;
    }
    else
    {
        currentData.links = currentData.links.concat(data.links);
        currentData.nodes = currentData.nodes.concat(data.nodes);
    }

    return data.next_cursor_str;
}

function loadCloud(data)
{
    force.nodes(data.nodes)
        .links(data.links)
        .charge(-120)
        .linkDistance(30)
        .start();

    var link = svg.selectAll(".link")
        .data(data.links)
        .enter()
            .append("line")
            .attr("class", "link")
            .style("stroke-width", function(d) { return Math.sqrt(d.value); });

/*
    var filters = svg.selectAll(".filters")
        .data(data.nodes)
        .enter()
            .append("filter")
            .attr("class", "filters")
            .attr("id", function(d){ return 'filter_' + d.id; })
            .attr("x", "0%")
            .attr("y", "0%")
            .attr("width", "100%")
            .attr("height", "100%")
            .append("feImage")
                .attr("xlink:href", function(d){ return d.profile_image_url; });
*/

/*
    var node = svg.selectAll(".node")
        .data(data.nodes)
        .enter()
        .append("circle")
            .attr("class", "node")
            .attr("r", 10)
            .attr("filter", function(d){ return 'url(#filter_' + d.id + ')'; })
            .call(force.drag);
*/
    var node = svg.selectAll(".node")
            .data(data.nodes)
        .enter().append("circle")
            .attr("class", "node")
            .attr("r", 5)
            .style("fill", '#00000')
            .call(force.drag);

    force.on("tick", function() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
    });
}

function remapUserData(response)
{
    var newdata = {
        nodes:[
            response.loggeduser
        ],
        links:[]
    };

    var targetBase = currentData ? currentData.links.length : 0;

    response.data.forEach(function(e, i){
        newdata.nodes.push(e);
        newdata.links.push({
            source:0,
            target:targetBase+i+1,
            value:1
        });
    });

    newdata.next_cursor_str = response.next_cursor_str;

    return newdata;
}