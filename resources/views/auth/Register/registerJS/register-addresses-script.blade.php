<script type="text/javascript">
    $(document).ready(function(){
        //$(".date_of_birth_cont .input-group.date").datepicker({});
        //var ids_home = new Array('unit_number', 'street_number', 'street_name', 'town_or_suburb', 'post_code', 'city', 'state', 'birth_country'), ids_mail = new Array('mail_unit_number', 'mail_street_number', 'mail_street_name', 'mail_town_or_suburb', 'mail_post_code', 'mail_city', 'mail_state', 'mail_country')
        //var ids_home = new Array('unit_number', 'street_number', 'street_name', 'town_or_suburb', 'post_code', 'state', 'birth_country'), ids_mail = new Array('mail_unit_number', 'mail_street_number', 'mail_street_name', 'mail_town_or_suburb', 'mail_post_code', 'mail_state', 'mail_country')
        var ids_home = new Array('address_line_1', 'address_line_2', 'town_or_suburb', 'post_code', 'state', 'birth_country'), ids_mail = new Array('mail_address_line_1', 'mail_address_line_2', 'mail_town_or_suburb', 'mail_post_code', 'mail_state', 'mail_country')

        $('#mail_home_owner').change(function(){
            init_mail_home_owner(ids_home, ids_mail);
        });

        init_mail_home_owner(ids_home, ids_mail, $('#mail_home_owner'));

        $('#mail_home_owner').on('click', function(event){
            init_mail_home_owner(ids_home, ids_mail, this);
            if($(this).is(":checked"))
                $("#mailing-address-container").toggleClass("in");
            else
                $("#mailing-address-container").toggleClass("in");
        });

        for(indexes in ids_home){
            $("#"+ids_mail[indexes]).off();
            $("#"+ids_mail[indexes]).on('keydown', function(){
                if($("#"+ids_home[indexes]).val() != $("#"+ids_mail[indexes]).val() && $("#mail_home_owner").is(':checked'))
                    $("#mail_home_owner").prop('checked', false);
            });
        }
        if($('#prev_home_addr').is(':checked'))
            $(".prev-home-addr-form").toggleClass('init-hide');
        $('#prev_home_addr').on('click', function(){
            $(".prev-home-addr-form").toggleClass('init-hide');
            if(!$(".prev-home-addr-form").hasClass('init-hide'))
                $(".prev-home-addr-form input").prop('required',true);
            else
                $(".prev-home-addr-form input").prop('required',false);
        });

        function init_mail_home_owner(ids_home, ids_mail, elem){
            if($(elem).is(":checked")){
                for(indexes in ids_home){
                    $("#"+ids_mail[indexes]).val($("#"+ids_home[indexes]).val());
                    $("#"+ids_home[indexes]).off();
                    $("#"+ids_home[indexes]).on('keydown', function(){
                        $("#"+ids_mail[indexes]).val($(this).val());
                    });
                }
            }
        }

        function updateIdsNames(){
            var prev_forms_count = $(".prev-home-addr-form").length, count = 1;
            $(".prev-home-addr-form").each(function(){
                $(this).find("label").each(function(){
                    var for_str = $(this).attr("for");
                    $(this).attr("for", for_str.substring(0, for_str.length-1)+count);
                });
                $(this).find("input").each(function(){
                    var _str = $(this).attr("id");
                    $(this).attr("id", _str.substring(0, _str.length-1)+count);
                    _str = $(this).attr("name");
                    $(this).attr("name", _str.substring(0, _str.length-1)+count);
                });
                count++;
            });
        }

        function removeAllPrevForms(){
            $.post({
                url: "/register/remove_all_prev_address",
                data: {"_token": window.Laravel['csrfToken']},
                success: function(){
                    $(".ajax-group").html('');
                },
                fail: function(e){
                    console.log(e);
                }
            });
        }

        function removeSpecificPrevForm(elem, index){
            $.post({
                url: "/register/remove_prev_address",
                data: {"_token": window.Laravel['csrfToken'], 'index': index},
                success: function(){
                    elem.remove();
                    updateIdsNames();
                },
                fail: function(e){
                    console.log(e);
                }
            });
        }

        function reInitButtons(){
            $(".remove-button").off();
            $(".remove-button").on("click", function(){
                removeSpecificPrevForm($(this).parents(".prev-home-addr-form").parents(".form-group"), $(this).attr('data-index'));
            });

            $(".add-button-wrapper").off();
            $(".add-button-wrapper").on("click", function(){
                $(this).remove();
                addPrevForm();
            });
        }

        function addPrevForm(){
            var _index = $(".prev-home-addr-form").length+1;
            $.post({
                url: "/register/add_prev_address",
                data: {"_token": window.Laravel['csrfToken']},
                success: function(data){
                    $(".ajax-group").append(data);
                    $('#prev_home_from_date_'+_index)
                        .datepicker({
                            format: 'dd/mm/yyyy',
                            startView: "decade",
                            endDate: '+0d',
                            autoclose: true
                        }
                    );
                    $('#prev_home_to_date_'+_index)
                        .datepicker({
                            format: 'dd/mm/yyyy',
                            startView: "decade",
                            endDate: '+0d',
                            autoclose: true
                        }
                    );
                    reInitButtons();
                },
                fail: function(data){
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        function loadPrevForm(){
            var _index = $(".prev-home-addr-form").length+1;
            $.post({
                url: "/register/load_prev_address",
                data: {"_token": window.Laravel['csrfToken'], "index":_index},
                success: function(data){
                    $(".ajax-group").append(data);
                    var prev_form_count = $(".prev-home-addr-form").length;
                    for(var i = 0; i <= prev_form_count; i++){
                        $('#prev_home_from_date_'+i)
                            .datepicker({
                                format: 'dd/mm/yyyy',
                                startView: "decade",
                                endDate: '+0d',
                                autoclose: true
                            }
                        );
                        $('#prev_home_to_date_'+i)
                            .datepicker({
                                format: 'dd/mm/yyyy',
                                startView: "decade",
                                endDate: '+0d',
                                autoclose: true
                            }
                        );
                    }
                    reInitButtons();
                },
                fail: function(data){
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }

        if($("#length_of_time").val() == "0"){
            loadPrevForm();
            $(".prev-address-group").removeClass("init-hide");
        }

        $("#length_of_time").on("change", function(){
            if($(this).val() == "0"){
                addPrevForm();
                $(".prev-address-group").toggleClass("init-hide");
            }else{
                removeAllPrevForms()
                $(".prev-address-group").toggleClass("init-hide");
            }
        });
    });
</script>