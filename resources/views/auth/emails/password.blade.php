<!DOCTYPE html>
<html>
<body>

<div style="background:#ecf2fc;padding:20px">
    <div style="margin:0 auto;padding:20px;background:white; color: #999999;">
        <div style="min-height:31px">

            <div style="float:left">
                <h2> {{ get_option('site_name') }}</h2>
            </div>

        </div>
        <div style="clear:both"></div>

        <h2 class="padding-top:0px;font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;color:#205081;font-size:20px;line-height:32px;text-align:left;font-weight:bold">  Don't worry, we all forget sometimes </h2>


        <div>
            <hr style="border-color: #EEEEEE; border-style: solid;">
            <div>
                <br />

                Click here to reset your password: {{ url('password/reset/'.$token) }}

                <hr style="border-color: #EEEEEE; border-style: solid;">

                <a href="{{ url('password/reset/'.$token) }}" shape="rect" style="font-size:16px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#ffffff;text-decoration:none;background-color:#3572b0;border-top:11px solid #3572b0;border-bottom:11px solid #3572b0;border-left:20px solid #3572b0;border-right:20px solid #3572b0;border-radius:5px;display:inline-block"> Reset my password</a>

            </div>

        </div>
    </div>
</div>

</body>
</html>