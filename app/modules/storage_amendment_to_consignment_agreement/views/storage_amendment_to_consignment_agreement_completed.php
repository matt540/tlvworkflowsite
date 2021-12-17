<div id="login" class="flex-scrollable" layout="column" ms-scroll>
    <div id="login-form-wrapper" layout="column" layout-align="center center">
        <div id="login-form" class="md-whiteframe-8dp">
     

            <div ng-if="title!=''" class="title"><h2 style="font-size: 50px;">{{title}}</h2></div>
            <div ng-if="message!=''" class="title"><h2>{{message}}</h2></div>
            
        </div>
    </div>
</div>


<style>
    #login #login-form-wrapper #login-form{
        width: 65% !important; 
        max-width: none !important; 
        padding: 50px;
        background: #FFFFFF;
        text-align: center;
        border-radius: 8px;
    }
    ol {
        margin:0 0 1.5em;
        padding:0;
        counter-reset:item;
    }

    ol>li {
        margin:0;
        padding:0 0 0 2em;
        text-indent:-2em;
        list-style-type:none;
        counter-increment:item;
    }

    ol>li:before {
        display:inline-block;
        width:1.5em;
        padding-right:0.5em;
        font-weight:bold;
        text-align:right;
        content:counter(item) ".";
    }
    .input_text_box{
        color: rgba(0, 0, 0, 0.87);
        border-top: none;
        border-right: none;
        border-left: none;
        border-image: initial;
        border-bottom: 1px solid;
        border-color: #e3e3e3;
        padding: 5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    .md-datepicker-input{
        width:328px;
    }

    .result {
        border: 1px solid blue;
        margin: 30px auto 0 auto;
        height: 100px;
        width: 220px;
    }
    input.ng-invalid{
        border-color: rgb(213,0,0);
    }
</style>