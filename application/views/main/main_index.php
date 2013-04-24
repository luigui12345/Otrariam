<nav>
    <ul id="center_nav">
        <li id="menu_logo"><img src="design/logo.png" id="logo"></li>
        <li id="menu_login">
            <div id="login"><p><?php echo lang('login');?></p></div>
            <!--Login-->
            <form class="form-1" id="form_login" name="form_login" method="post" action="main/login/">
                <p class="field">
                    <input type="text" placeholder="<?php echo lang('username');?>" maxlength="30" name="username" required>
                    <i class="icon-user"></i>
                </p>
                <p class="field">
                    <input type="password" placeholder="<?php echo lang('password');?>" maxlength="30" name="password" required/>
                    <i class="icon-lock"></i>
                </p>       
                <p class="submit">
                    <button type="submit" name="submit"><i class="icon-arrow-right icon-large"></i></button>
                </p>
                <p class="recordar">
                    <input type="checkbox"> <?php echo lang('remember');?>
                </p>
            </form>
        </li>
        <a href="https://github.com/Flash-back/Otrariam" target="_blank" title="Descargar código fuente">
            <li id="menu_github">
                <i class="icon-github"></i>
            </li>
        </a>
    </ul><!--center_nav-->
</nav>
<div id="wrap">
    <div id="center_wrap">
        <div id="intro">
            <h2>Expande. Conquista. Domina.</h2><br>
            <p>
                <strong><?php echo $this->config->item('game_name');?></strong> <?php echo lang('slogan');?>
            </p>
        </div>
		<div id="menu_left">
            <div class="slider">
                <input type="radio" name="slide_switch" id="id1"/>
                <label for="id1">
                    <img src="design/slider/slider2.png"/>
                </label>
                <img src="design/slider/slider2.png"/>
    
                <!--Lets show the second image by default on page load-->
                <input type="radio" name="slide_switch" id="id2" checked="checked"/>
                <label for="id2">
                    <img src="design/slider/slider0.png" width="100"/>
                </label>
                <img src="design/slider/slider0.png"/>
    
                <input type="radio" name="slide_switch" id="id3"/>
                <label for="id3">
                    <img src="design/slider/slider1.png" width="100"/>
                </label>
                <img src="design/slider/slider1.png"/>
    
                <input type="radio" name="slide_switch" id="id4"/>
                <label for="id4">
                    <img src="design/slider/slider3.png" width="100"/>
                </label>
                <img src="design/slider/slider3.png"/>

                <input type="radio" name="slide_switch" id="id5"/>
                <label for="id5">
                    <img src="design/slider/slider4.png" width="100"/>
                </label>
                <img src="design/slider/slider4.png"/>
            </div>
		</div><!--menu_right-->
        <div id="menu_right">	
            <div id="wrap_register">
                <div id="intro_register">
                    <span><?php echo lang('register');?></span><br/>
                    <p><?php echo lang('reg_description');?></p>
                </div> 
                <div id="register">
				    <form class="form" name="form_register" method="post" action="main/register/">
                        <div class="section_input">
                            <p class="field">
                                <input type="text" name="username" placeholder="<?php echo lang('username');?>" maxlength="50" class="input">
                                <i class="icon-user icon-large"></i>
                            </p>
                        </div>
                        <div class="section_input">
                            <p class="field">
                                <input type="password" name="password" placeholder="<?php echo lang('password');?>" maxlength="50" class="input">
                                <i class="icon-lock icon-large"></i>
                            </p>
                        </div>
                       <div class="section_input">
                            <p class="field">
                                 <input type="text" name="email" placeholder="<?php echo lang('email');?>" maxlength="50" class="input">
                                 <i class="icon-envelope icon-large"></i>
                            </p>
                        </div>
                        <div class="section_input" style="color:red;">
                            <p><?php //$log->interpretaError();?></p>
                        </div>
                        <input type="submit" name="register" value="<?php echo lang('reg_button');?>" class="input_login">
                    </form>
				</div><!--#register-->
            </div><!--#wrap_register-->
    </div><!--menu_left-->
</div><!--center_wrap-->
</div><!--Wrap-->