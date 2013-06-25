<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Unamed</title>
    <!-- Begin CSS/JS -->
    <?php getAdminHead();?>
    <!-- End CSS/JS -->
</head>
<body>
<!-- Begin Structure -->
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="nav-collapse collapse">
                    <ul class="nav top">
                        <li>
                            <a href="/" class="no-ajaxy">Public Site</a>
                        </li>
                        <li>
                            <a href="<?php adminUrl();?>">Overview</a>
                        </li>
                    </ul>
                    <ul class="nav top-notifications-user pull-right">
                        <li class="dropdown">
                            <a  class="no-ajaxy dropdown-toggle notifications" data-toggle="dropdown" href="<?php adminUrl();?>notifications">
                                <i class="icon-flag"></i> 
                                <span class="badge badge-important">6</span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="divider"></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a  class="no-ajaxy dropdown-toggle" data-toggle="dropdown" href="<?php adminUrl();?>users/edit/1">
                                <span>admin</span> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <img src="/" class="img-polaroid" style="width:30px;height:auto;" alt="" />
                                    <span>admin</span>
                                </li>
                                <li><a href="<?php adminUrl();?>users/edit/:id"><span>edit</span></a></li>
                                <li><a href="<?php adminUrl();?>users/logout"><span>log out</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top:55px;">
        <div class="row">
            <div id="left" class="span2">
                <ul class="main nav nav-list affix">
                    <li><a href="<?php adminUrl();?>"><span>Overview</span></a></li>
                    <li><a href="<?php adminUrl();?>posts"><span>Posts</span></a></li>
                    <li><a href="<?php adminUrl();?>plugins"><span>Plugins</span></a></li>
                    <li><a href="<?php adminUrl();?>themes"><span>Themes</span></a></li>
                    <li><a href="<?php adminUrl();?>users"><span>Users</span></a></li>
                    <li><a href="<?php adminUrl();?>settings"><span>Settings</span></a></li>
                </ul>
            </div>
            <div  id="content" class="span10">