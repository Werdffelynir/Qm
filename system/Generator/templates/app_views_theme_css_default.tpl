/* LITE GRID STYLE */

/* Reset */
*{margin: 0; padding: 0; border:none; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;}

blockquote, q {quotes:none}
table, table td {padding:0;border:none;border-collapse:collapse}
img {vertical-align:top}
embed {vertical-align:top}

/* Getting the new tags to behave */
article, aside, audio, canvas, command, datalist, details, embed, figcaption, figure, footer, header, hgroup, keygen, meter, nav, output, progress, section, source, video {display:block}
mark, rp, rt, ruby, summary, time {display:inline}

/* Grids */
.lite_1,
.lite_2,
.lite_3,
.lite_4,
.lite_5,
.lite_6,
.lite_7,
.lite_8,
.lite_9,
.lite_10,
.lite_11{display:inline; float:left; margin:0 0 0 1.0416666666667%; list-style:none;}
.first{margin-left:0; clear:left;}
.clear_line{clear: both; width: 100%; height: 1px; margin-top: -1px;}
.clear:before, .clear:after{content: " "; display: table;}
.clear:after {clear: both;}
.clear {*zoom: 1;}
.full{display:block; width:100%; clear:both;}
.lite{display:block; width:100%; clear:both;}
.lite_1:first-child,
.lite_2:first-child,
.lite_3:first-child,
.lite_4:first-child,
.lite_5:first-child,
.lite_6:first-child,
.lite_7:first-child,
.lite_8:first-child,
.lite_9:first-child,
.lite_10:first-child,
.lite_11:first-child{margin-left:0;}
.lite_1{width:7.3784722222222%;}
.lite_2{width:15.798611111111%;}
.lite_3{width:24.21875%;}
.lite_4{width:32.638888888889%;}
.lite_5{width:41.059027777778%;}
.lite_6{width:49.479166666667%;}
.lite_7{width:57.899305555556%;}
.lite_8{width:66.319444444444%;}
.lite_9{width:74.739583333333%;}
.lite_10{width:83.159722222222%;}
.lite_11{width:91.579861111111%;}

/* Left & Right alignment */
.hide{visibility: hidden;}
.al_l{text-align: left;}
.al_r{text-align: right;}
.al_c{text-align: center;}
.mg_t{margin-top: 0;}
.mg_b{margin-bottom: 0;}
.mg_l{margin-left: 0;}
.mg_r{margin-right: 0;}
.fl_l {float:left}
.fl_r {float:right}


/* site style */
ul{
    padding-left: 25px;
}

a{
    color:#993300;
}
a:hover{
    color:#993300;
}

p{
    margin: 5px 0 10px 0;
    text-indent: 15px;
}
form input{
    padding: 4px;
    border: 2px solid #462364;
}
form input[type=submit]{
    padding: 2px 25px;
    color: mistyrose;
    background-color: #462364;
}
form div{
    margin-bottom: 5px;
}

html, body{
    background: #6DA0E3;
    height: 100%;
    font-family: Tahoma, Geneva, sans-serif;
    font-size: 12px;
}

#wrapper{
    width: 960px;
    margin: 0 auto;
    background: #EEF4FC;
    padding: 10px;
    box-shadow: 0 0 15px 2px #737373;
}
#header{
    height: 60px;
}
#topMenu{
    padding: 5px;
    border-top: 1px solid #663399;
    border-bottom: 1px solid #663399;
    margin-bottom: 5px;
}
#page{}
#content{
    padding: 0 10px;
    min-height: 500px;
}
#sidebar{}
#footer{
    padding: 10px;
    margin-top: 5px;
    border-top: 1px solid #663399;

}

.logo{
    font-family: Tahoma, Geneva, sans-serif;
    font-size: 36px;
    font-weight: bold;
    color: #F500F5;
    text-shadow: 0px 1px 0px #FFFFFF;
}
.logoText{
    color: #F500F5;
    display: inline-block;
    font-family: Tahoma,Geneva,sans-serif;
    font-size: 18px;
    margin-top: 8px;
    text-shadow: 0px 1px 0px #FFFFFF;
}

.loginBox{
    display: block;
    height: 50px;
    border-radius: 4px;
    padding: 5px;
    color: #6633CC;
    text-align: right;
}
.loginBox a{
    color: #660066;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
}
.loginBox a:hover{
    color:#6633CC;
}

#topMenu a{
    display: inline-block;
    background: #660066;
    color: #FFBDFF;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 4px;
    padding: 2px 5px;
    -webkit-transition: all ease 0.4s;
    -moz-transition: all ease 0.4s;
    transition: all ease 0.4s;
}
#topMenu a:hover{
    background: #F500F5;
    color: #FFFFFF;
    box-shadow: 0px 1px 0px #FFFFFF;
}

#sidebar h2{
    width: 110%;
    margin-bottom: 8px;
    padding: 2px 6px;
    color: #FFD1FF;
    display: block;
    font-family: Tahoma,Geneva,sans-serif;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0px 0px 3px 0px #663399;
    border-bottom: 1px solid #081021;
    border-radius: 7px;
    background-color: #660066;

}

#sidebar h2:hover{
}

#sidebar span{
    display: block;
    color: #002538;
    margin-bottom: 12px;
}

#footer{
    color: #1AB2FF;
    display: inline-block;
    font-family: Tahoma,Geneva,sans-serif;
    font-size: 12px;
    margin-top: 8px;
    text-align: center;
    text-shadow: 0px 1px 0px #FFFFFF;
}
