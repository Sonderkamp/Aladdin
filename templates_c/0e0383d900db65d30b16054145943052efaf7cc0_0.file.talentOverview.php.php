<?php
/* Smarty version 3.1.29, created on 2016-03-04 12:58:46
  from "C:\xampp\htdocs\View\talentOverview.php" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56d9787685d878_62268485',
  'file_dependency' => 
  array (
    '0e0383d900db65d30b16054145943052efaf7cc0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\View\\talentOverview.php',
      1 => 1457092724,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56d9787685d878_62268485 ($_smarty_tpl) {
?>

<!--Created by PhpStorm.-->
<!--User: Joost-->
<!--Date: 27-2-2016-->
<!--Time: 21:48-->

<div class="container">
    <div class="col-sm-12 col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Toegevoegde talenten</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
$_from = $_smarty_tpl->tpl_vars['talents']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_talent_0_saved_item = isset($_smarty_tpl->tpl_vars['talent']) ? $_smarty_tpl->tpl_vars['talent'] : false;
$_smarty_tpl->tpl_vars['talent'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['talent']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['talent']->value) {
$_smarty_tpl->tpl_vars['talent']->_loop = true;
$__foreach_talent_0_saved_local_item = $_smarty_tpl->tpl_vars['talent'];
?>
                    <tr>
                        <td class="col-sm-12"><?php echo $_smarty_tpl->tpl_vars['talent']->value->talent;?>
</td>
                        <td class="col-sm-1">
                            <button type="button" class="btn btn-inbox btn-sm" data-toggle="modal" data-target="#myModal<?php echo preg_replace('/\s+/','',$_smarty_tpl->tpl_vars['talent']->value->talent);?>
">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </td>
                    </tr>
                <?php
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_0_saved_local_item;
}
if ($__foreach_talent_0_saved_item) {
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_0_saved_item;
}
?>
            </tbody>
        </table>
    </div>
    <div class="col-sm-12 col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Alle talenten</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
$_from = $_smarty_tpl->tpl_vars['talents']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_talent_1_saved_item = isset($_smarty_tpl->tpl_vars['talent']) ? $_smarty_tpl->tpl_vars['talent'] : false;
$_smarty_tpl->tpl_vars['talent'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['talent']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['talent']->value) {
$_smarty_tpl->tpl_vars['talent']->_loop = true;
$__foreach_talent_1_saved_local_item = $_smarty_tpl->tpl_vars['talent'];
?>
                    <tr>
                        <td class="col-sm-12"><?php echo $_smarty_tpl->tpl_vars['talent']->value->talent;?>
</td>
                        <td class="col-sm-1">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal<?php echo preg_replace('/\s+/','',$_smarty_tpl->tpl_vars['talent']->value->talent);?>
">
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                        </td>
                    </tr>
                <?php
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_1_saved_local_item;
}
if ($__foreach_talent_1_saved_item) {
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_1_saved_item;
}
?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<?php
$_from = $_smarty_tpl->tpl_vars['talents']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_talent_2_saved_item = isset($_smarty_tpl->tpl_vars['talent']) ? $_smarty_tpl->tpl_vars['talent'] : false;
$_smarty_tpl->tpl_vars['talent'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['talent']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['talent']->value) {
$_smarty_tpl->tpl_vars['talent']->_loop = true;
$__foreach_talent_2_saved_local_item = $_smarty_tpl->tpl_vars['talent'];
?>
<div id="myModal<?php echo preg_replace('/\s+/','',$_smarty_tpl->tpl_vars['talent']->value->talent);?>
" class="modal fade" role="dialog">
  <div class="modal-dialog">

      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Talent verwijderen</h4>
            </div>
            <div class="modal-body">
                <p>
                    Weet u zeker dat u het talent "<?php echo $_smarty_tpl->tpl_vars['talent']->value->talent;?>
" wilt verwijderen?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Sluiten</button>
                <form action="/talents" method="post">
                    <input type="hidden" name="talent" value="<?php echo $_smarty_tpl->tpl_vars['talent']->value->talent;?>
"/>
                    <button type="submit" name="submit" class="btn btn-inbox info"><span class="glyphicon glyphicon-remove"></span> Verwijderen</button>
                </form>
            </div>
        </div>

    </div>
</div>
<?php
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_2_saved_local_item;
}
if ($__foreach_talent_2_saved_item) {
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_2_saved_item;
}
}
}
