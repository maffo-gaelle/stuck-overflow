<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Graphique Histogramme</title>
        <base href="<?= $web_root ?>"/>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" type="text/css" />
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" type="text/css" />
        <link rel="stylesheet" href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" type="text/css" />
        
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>        
        
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script src="js/stats.js" type="text/javascript"></script>
        <script src="js/menu.js" type="text/javascript"></script>
    </head>
    <body>
        <?php include("menu.php") ?>

        <div class="jumbotron">
            <div>
                <input id="period" value="1" type = "number" style=" width: 150px; height:30px;">
                <select id = "temp" style = "width : 150px; height:40px;">
                    <option value='days'> Day(s) </option>
                    <option value = 'weeks'> Week(s)</option>
                    <option value = 'months'> Month(s)</option>
                    <option value = 'years'> Year(s)</option>
                </select> 
                
                <canvas id="graph"  width="20%" height="5%"></canvas>
            </div>

            <div id = "details">
            
            </div>
        </div>
    </body>
</html>