<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список стран</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
        body { 
            display: none; 
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
      	    $("body").css("display", "none");
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("body").css("display", "none");
            $("body").fadeIn(1000);
	        $("a.transition").click(function(event){
	    	event.preventDefault();
		    linkLocation = this.href;
	    	$("body").fadeOut(1000, redirectPage);
	        });
        function redirectPage() {
		    window.location = linkLocation;
	        }
        });
</script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Список стран</h2>
                        <a href="create.php" class="btn btn-success pull-right">Добавить</a>
                    </div>
                    <?php
                    require_once "config.php";
                    
                    // Создание запроса
                    $sql = "SELECT * FROM country";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Страна</th>";
                                        echo "<th>Столица</th>";
                                        echo "<th>Кол-во жителей</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['capital'] . "</td>";
                                        echo "<td>" . $row['people'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='read.php?id=". $row['id'] ."' title='Просмотр' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open' class='transition'></span></a>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Изменение' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil' class='transition'></span></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Удаление' data-toggle='tooltip'><span class='glyphicon glyphicon-trash' class='transition'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>Нет записей для отображения</em></p>";
                        }
                    } else{
                        echo "Ошибка : $sql. " . mysqli_error($link);
                    }
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>