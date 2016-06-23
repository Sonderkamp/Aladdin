<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2-3-2016
 * Time: 00:39
 */
?>
<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">


    <div class="col-sm-9 ">


        {if $message->adminSender}
        <div class="panel panel-default adminMessage">
            {else}
            <div class="panel panel-default">
                {/if}
                <div class="panel-body message">
                    <div>
                        <span class="h3 title">{htmlspecialchars($message->title)}</span>
                    <span class="info">
                    {if $message->adminSender}
                        Van:
                        <span class="glyphicon glyphicon-eye-open"></span>
                        {$message->sender}
                    {else}
                        Van: {$message->sender}
                    {/if}
                        <br>Naar: {$message->receiver}
                        <br>{$message->date}
                        </span>
                    </div>

                    <br>
                    <span>{$message->content}</span><br><br>
                </div>
                <div class="panel-footer">

                </div>
            </div>
        </div>


    </div>


</div>

</body>
</html>