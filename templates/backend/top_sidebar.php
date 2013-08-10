<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
        <div class="container">
            
            <a class="brand"><?php echo VERSION ?></a>
            
                <ul class="nav">

                    <li <?php echo (!$this->uri->segment(2) ? 'class="active"' : '') ?>><a href="/backend/">Главная</a></li>
                    
                    <li class="dropdown <?php echo ($this->uri->segment(2) == 'news' ? 'active' : '') ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown">Новости <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/backend/news/">Просмотр</a></li>
                            <li><a href="/backend/news/add/">Добавить</a></li>
                        </ul>
                    </li>

                    <li class="dropdown <?php echo ($this->uri->segment(2) == 'pages' ? 'active' : '') ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown">Страницы <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/backend/pages/">Просмотр</a></li>
                            <li><a href="/backend/pages/add/">Добавить</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown <?php echo ($this->uri->segment(2) == 'settings' ? 'active' : '') ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown">Настройки <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/backend/settings/add/">Добавить настройку</a></li>
                            <?php foreach($settings_group as $id => $name) { ?>
                                <li><a href="/backend/settings/group/<?php echo $id ?>/"><?php echo $name ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    
                    <li class="dropdown <?php echo ($this->uri->segment(2) == 'users' ? 'active' : '') ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown">Пользователи <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/backend/users/">Просмотр</a></li>
                            <li><a href="/backend/users/add/">Добавить</a></li>
                        </ul>
                    </li>
                    
                    <!-- LINEAGE -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown">Lineage2 <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header">Игровые сервера</li>
                            <li><a href="/backend/servers/">Просмотр</a></li>
                            <li><a href="/backend/servers/add/">Добавить</a></li>
                            
                            <li class="divider"></li>
                            <li class="nav-header">Логин сервера</li>
                            <li><a href="/backend/logins/">Просмотр</a></li>
                            <li><a href="/backend/logins/add/">Добавить</a></li>

                            <li class="divider"></li>
                            <li class="nav-header">Аккаунты</li>
                            <li><a href="/backend/accounts/">Просмотр</a></li>
                            
                            <li class="divider"></li>
                            <li class="nav-header">Персонажи</li>
                            <li><a href="/backend/characters/">Просмотр</a></li>
                            
                        </ul>
                    </li>
                    
                    <li <?php echo ($this->uri->segment(2) == 'telnet' ? 'class="active"' : '') ?>><a href="/backend/telnet/">Telnet</a></li>

                    <li class="dropdown <?php echo ($this->uri->segment(2) == 'gallery' ? 'active' : '') ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown">Галерея <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/backend/gallery/">Просмотр</a></li>
                            <li><a href="/backend/gallery/add/">Добавить</a></li>
                        </ul>
                    </li>

                    <li><a href="/backend/themes/">Шаблоны</a></li>
                    
                    <li><a href="/">На сайт</a></li>

               </ul>
        </div>
    </div><!-- /navbar-inner -->
</div>