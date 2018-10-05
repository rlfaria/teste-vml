<!doctype html>
<html lang="pt-br">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<script src="node_modules/chart.js/dist/Chart.bundle.js"></script>
<script src="node_modules/chart.js/samples/utils.js"></script>
<link rel="stylesheet" href="node_modules/bootstrap/compiler/bootstrap.css">
<link rel="stylesheet" href="node_modules/bootstrap/compiler/style.css">
<style>
		canvas{
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
</style>
<title>Teste - VML</title>
</head>
<body>
<?php include 'inc_navbar.php';?>
<div class="container">
    <?php include 'inc_cards.php';?> 
</div>
<?php include 'inc_footer.php';?>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="node_modules/jquery/dist/jquery.js"></script>
<script src="node_modules/popper.js/dist/umd/popper.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
<script src="js/moment.js"></script>
<script>
var urlRepo = "https://api.github.com/users/globocom/repos";
var repos;

function showData(item){
    $("#total-stars").text(item.stargazers_count);
    $("#total-forks").text(item.forks_count);        
    $.getJSON(item.contributors_url,function(data){
        $("#total-contribs").text(data.length);
    })
    $.getJSON(item.commits_url.replace('{/sha}', ''),function(data){
        var commitsByMonth = [];
        for(var i = 0; i < data.length; i++) {
            var commit = data[i];
            var commitMonth = moment();
            var commitLabel = moment(commit.commit.author.date).format('MM-YYYY');
            
            if(!commitsByMonth[commitLabel]){
                commitsByMonth[commitLabel] = []; 
            }
            commitsByMonth[commitLabel].push(commit);
        }
        
        var dataChart = [];
        var labelsChart = [];
              
        for(commitLabel in commitsByMonth) {
            var commits = commitsByMonth[commitLabel];
            labelsChart.push(commitLabel);
            dataChart.push(commits.length);
        }
        
        var lineChartData = {
            labels: labelsChart,
            datasets: [{
                label: 'Commits',
                borderColor: window.chartColors.blue,
                pointBackgroundColor: window.chartColors.blue,
                fill: false,
                data: dataChart
            }]
        };
        
        //console.log(dataChart)

        new Chart($("#chart").get(0), {
            type: 'line',
            data: lineChartData
        });
    })
} 

$.getJSON(urlRepo,function(data){
    //console.log(data);
    repos = data;
    totalStars = 0;
    totalForks = 0;
    totalContribs = 0;
    for(var i = 0; i < data.length; i++) {
        var item = data[i]
        //console.log(item)
        $option = $("<option></option>");
        $option.attr('value',i);
        $option.text(item.name);
        $('#repoList').append($option);    
    }
    
    showData(repos[0]);
    
    $("#repoList").change(function(){
        var item = repos[$(this).val()];
        showData(item);
    })
});
</script>
</body>
</html>