var force,svg;

function initCloud() {
    svg = d3.select("body").append("svg")
        .attr("width", 900)
        .attr("height", 900);

    force = d3.layout.force()
        .size([900, 900]);
}

function loadFriends()
{
    $.ajax({
        url: "twitter/friends"
    })
    .done()
    .then(remapUserData)
    .then(loadCloud);
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

function loadCloud(data)
{
    force.nodes(data.nodes)
        .links(data.links)
        .linkDistance(200)
        .gravity(0)
        .charge(-100)
        .start();

    var link = svg.selectAll(".link")
        .data(data.links)
        .enter()
            .append("line")
            .attr("class", "link")
            .style("stroke-width", function(d) { return Math.sqrt(d.value); });


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



    var node = svg.selectAll(".node")
        .data(data.nodes)
        .enter()
        .append("circle")
            .attr("class", "node")
            .attr("r", 20)
            .attr("filter", function(d){ return 'url(#filter_' + d.id + ')'; })
            .call(force.drag);

    //<rect x = "0" y = "0" width = "100%" height = "100%" filter = "url(#i1)"/>

    force.on("tick", function() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
    });
}

function remapUserData(data)
{
    var newdata = {
        nodes:[
            data.loggeduser
        ],
        links:[]
    };

    data.friends.forEach(function(e, i){
        newdata.nodes.push(e);
        newdata.links.push({
            source:0,
            target:i+1,
            value:1
        });
    });

    return newdata;
}