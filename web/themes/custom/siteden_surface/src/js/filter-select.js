document.addEventListener('DOMContentLoaded', () => {
    var mouseDown = 0;
    document.body.onmousedown = function() { 
        mouseDown = 1;
    }
    document.body.onmouseup = function() {
        mouseDown = 0;
    }
    var selectUls = document.getElementsByClassName("select-ul");

    jQuery('.hide-form-select').find('[selected="selected"]').each(function(){
        seletedVal = jQuery(this).val();
        jQuery(this).parent().parent().prev().find('[value="'+seletedVal+'"]').addClass('clicked');
    });
    Array.prototype.forEach.call(selectUls, function(el) {
        // Do stuff here
        el.addEventListener('mouseover', function(e) {
            e = e || window.event;
            e.preventDefault;
            var target = e.target || e.srcElement, text = target.textContent || target.innerText;           
            if(mouseDown) {  
                target.classList.add('clicked');
            }
            selected = '';
            jQuery('.clicked', this).each(function() {
                selected = selected + jQuery( this ).attr('value') + ',';
            });
            isMulti = jQuery(this).next('.hide-form-select').find('select').attr('multiple');
            if(isMulti == 'multiple'){
                jQuery(this).next('.hide-form-select').find('select').val(selected.split(','));
            } else {
                jQuery(this).next('.hide-form-select').find('select').val(selected.split(',')[0]);
            }
        }, false);
    });
    jQuery('.select-ul li').on('mousemove touchmove', function(event) {
        event.preventDefault();
    });
    jQuery('.select-ul').on('mousedown', function(event) {
        event.preventDefault();
        if (!event.shiftKey) {
            jQuery(this).find("li").removeClass('clicked');
        }
        jQuery(this).addClass('clicked');  // LINE MODIFIED
        selected = '';
        jQuery('.select-ul .clicked').each(function() {
            selected = selected + jQuery( this ).attr('value') + ',';
        });
        jQuery(this).next('.hide-form-select').find('select').val(selected.split(','));
    });
    jQuery(function() {   
        jQuery(".select-ul li").click(function (e) {
            if (!e.shiftKey) {
                jQuery(this).parent().find("li").removeClass('clicked');
            }
            jQuery(this).addClass('clicked');  // LINE MODIFIED
            selected = '';
            jQuery('.select-ul .clicked').each(function() {
                selected = selected + jQuery( this ).attr('value') + ',';
            });
            jQuery(this).next('.hide-form-select').find('select').val(selected.split(','));
        });
    });
    Array.prototype.forEach.call(selectUls, function(el) {
        // Do stuff here
        el.addEventListener("mousedown", function(e){
            e = e || window.event;
            e.preventDefault;
            var target = e.target || e.srcElement, text = target.textContent || target.innerText;           
            target.classList.add('clicked');
            selected = '';
            jQuery('.select-ul .clicked').each(function() {
                selected = selected + jQuery( this ).attr('value') + ',';
            });
            isMulti = jQuery(this).next('.hide-form-select').find('select').attr('multiple');
            if(isMulti == 'multiple'){
                jQuery(this).next('.hide-form-select').find('select').val(selected.split(','));
            } else {
                jQuery(this).next('.hide-form-select').find('select').val(selected.split(',')[0]);
            }
        });
    });
});