 <script>
 $(document).ready(function(){
        $('body').on('click', '#Certificate-Of-Currency', function(){
            var id = this.value;
            $('.coc-download-button').attr('href', "{{ route('download-coc') }}/" + id);
        });

        $('body').on('click', '.coc-download-button', function(e){      
            if( $('#Certificate-Of-Currency').val() == ""){     
                $('#Certificate-Of-Currency').css('border-color','red');        
                e.preventDefault();     
            }       
            else{       
         
            }       
         
        });     
        $('body').on('click', '.submit-amend', function(e){     
            e.preventDefault();     
        
            if( $('#Policy-Amendment-Request').val() == "" || $('.message_details').val() == "" ){      
                $('#Policy-Amendment-Request').css('border-color','red');       
                $('.message_details').css('border-color','red');        
                e.preventDefault();     
            }       
            else{       
                $.ajax({        
                  url: "/policy/amend",     
                  data: $('form').serialize(),      
                  success: function(data){      
                    $('#myModal').modal('show');        
                  }     
                });     
            }       
        });
    });
 </script>