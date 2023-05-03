<form action="" method="post" name="frmCont" id="frmCont" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
  <div class="form-body">
    <!-- <div class="form-group">
      <label for="benefits" class="control-label col-md-3"><font color="#FF0000">*</font>Content Types: &nbsp;</label>
      <div class="col-md-4">
        %CONSTANT_NAME_FIELD%
      </div>
    </div> -->
    <div class="clearfix"></div>
    %CONSTANT_VALUE%
    <div class="form-group">
      <label for="typeValue[%ID%]" class="control-label col-md-3"> %MEND_SIGN%
        Values :  &nbsp;
      </label>
      <div class="col-md-4">
        <input type="text" class="form-control logintextbox-bg required" name="value" id="value" value="%VALUE%">
      </div>
    </div>
  
    <?php if(in_array('status',$this->Permission)){ ?>
    <div class="form-group">
      <label class="control-label col-md-3">Status: &nbsp;</label>
      <div class="col-md-4">
        <div class="radio-list">
          <label class="">
            <input class="radioBtn-bg required" id="y" name="status" type="radio" value="y" %STATUS_A%>
            Active</label>
          <span for="status" class="help-block"></span>
          <label class="">
            <input class="radioBtn-bg required" id="n" name="status" type="radio" value="n" %STATUS_D%>
            Inactive</label>
          <span for="status" class="help-block"></span> </div>       
      </div>
    </div>
    <?php } ?>
    <div class="flclear clearfix"></div>
    <input type="hidden" name="type" id="type" value="%TYPE%">
    <div class="flclear clearfix"></div>
    <input type="hidden" name="id" id="id" value="%ID%">
    <div class="padtop20"></div>
  </div>
  <div class="form-actions fluid">
    <div class="col-md-offset-3 col-md-9">
      <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Submit</button>
      <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
    </div>
  </div>
</form>