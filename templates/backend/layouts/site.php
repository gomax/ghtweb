<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>GHTWEB - Админцентр</title>
    
    <!-- BOOTSTRAP -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    
    <!-- CHOSEN -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/libs/chosen/chosen.min.css" media="all" />
    
    <!-- TPL -->
    <link rel="stylesheet" href="/templates/<?php echo TPL_DIR ?>/css/style.css" type="text/css" media="screen, projection" />
    
</head>

<body>

    <?php echo !empty($message) ? $message : '' ?>

    <div class="wrapper">

        <div class="container">
            <?php echo $this->load->view('top_sidebar') ?>
    		<?php echo $content ?>
    	</div>
    	
        <div class="push"></div>
    </div>
	
	<footer class="page-footer">
		Спасибо что выбрали <a href="http://ghtweb.ru/" target="_blank">GHTWEB</a>
	</footer>
	

    <!-- JQUERY -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    
    <!-- BOOTSTRAP -->
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

    <!-- CHOSEN -->
    <script type="text/javascript" src="<?php echo base_url() ?>resources/libs/chosen/chosen.jquery.min.js"></script>

    <?php if(!us(2)) { ?>
        <script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript" src="//code.highcharts.com/modules/exporting.js"></script>
        <script type="text/javascript">
        $(function(){
            chart1 = new Highcharts.Chart({
                chart: {
                    renderTo: 'container_chart',
                    type: 'area'
                },
                title: {
                    text: 'График регистраций'
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: <?php echo $reg_data_time ?> // Даты
                },
                yAxis: {
                    title: {
                       text: ''
                    }
                },
                series: [{
                    name: 'Регистраций',
                    data: <?php echo $reg_data_count ?> // Кол-во регистраций
                }]
            });
        })
        </script>
    <?php } ?>

    <?php if((us(2) == 'gallery') || (us(2) == 'gallery' && us(3) == 'edit')) { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/jquery.fancybox.css" media="all" />
        <script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>

        <script type="text/javascript">
        $(function(){
            $('.fancybox').fancybox({
                padding: 0
            });
        })
        </script>

    <?php } ?>

    <?php if(us(2) == 'themes') { ?>
        <script type="text/javascript">
            $(window).load(function(){
                var fh = $('.themes li:first figure').height();
                $('figure').each(function(){
                    var img   = $(this).find('img'),
                        img_h = img.height(),
                        mt    = parseInt((fh - img_h) / 2);
                    
                    img.css('margin-top',mt);
                });
            })
        </script>
    <?php } ?>

    <script type="text/javascript" src="/templates/backend/js/main.js"></script>

</body>
</html>