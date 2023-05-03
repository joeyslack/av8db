<div class="form-group ckeditFld" id="descwrap_%LANGUAGE_NAME%" >
	<label for="%TYPEVALUE%[%ID%]" class="control-label col-md-3"> %MEND_SIGN%
		%LBL_NM% : (%LANGUAGE_NAME%) &nbsp;
	</label>
	<div class="col-md-9">
		<textarea class="ckeditor form-control textarea-bg %TYPEVALUE%" name="%TYPEVALUE%[%ID%]" id="%TYPEVALUE%[%ID%]" data-error-container="#editor_error_%ID%" style="display: none;">%CONSTANT_VALUE%</textarea>
		<div id="editor_error_%ID%"></div>
	</div>
</div>
<script type="text/javascript">$(function(){loadCKE("%TYPEVALUE%[%ID%]");});</script>
	