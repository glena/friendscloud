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


    var node = svg.selectAll(".node")
        .data(data.nodes)
        .enter()
        .append("circle")
        .attr("class", "node")
        .attr("r", 20)
        .call(force.drag);

    /*
    var node = svg.selectAll(".node")
        .data(data.nodes)
        .enter()
            .append("circle")
            .attr("r", 50)
            .attr("class", "node");

    node.append("image")
        .attr('width',30)
        .attr('height',30)
        .attr("xlink:href", function(d){return d.profile_image_url ? d.profile_image_url.replace('_normal','') : ''; });

    node.call(force.drag);
*/

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
        nodes:[{name:'currentuser'}],
        links:[]
    };

    data.forEach(function(e, i){
        newdata.nodes.push(e);
        newdata.links.push({
            source:0,
            target:i+1,
            value:1
        });
    });

    return newdata;
}