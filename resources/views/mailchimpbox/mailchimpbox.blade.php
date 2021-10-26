<?php 

// Parameters
//isset($request['audienceName']) ? $audienceName = $request['audienceName'] : $audienceName = '';
//isset($request['tagToApply']) ? $tagToApply = $request['tagToApply'] : $tagToApply = '';


// No point in showing the form if there's no where to send the data
if(!$audienceName) {return;}

?>


    <div class="mcbx-wrapper">
        <form id="mcbx-form" class="">
            <div class="mcbx-search-wrapper cf">
                <input type="email" name="email" placeholder="Your email" required style="box-shadow: none">
                <button type="submit">SUBSCRIBE</button>
            </div>
            <div class="mcbx-name-row closed">
                <input type="text" name="firstname" placeholder="First name" required style="box-shadow: none">
                <input type="text" name="lastname" placeholder="Last name" required style="box-shadow: none">
                <input type="hidden" name="audienceName" value="{{ $audienceName }}">
                <input type="hidden" name="tagToApply" value="{{ $tagToApply }}">
                <?php // TODO: add csrf token ?>
            </div>
        </form>

        <p class="mcbxResult"></p>
    </div>

    <style>
        /*Clearing Floats*/
        .cf:before, .cf:after{
            content:"";
            display:table;
        }

        .cf:after{
            clear:both;
        }

        .cf{
            zoom:1;
        }    
        /* Form wrapper styling */

        #mcbx-form {
            font-family: "Montserrat",Helvetica,Arial,Lucida,sans-serif;
            font-size: 1.3em;
        }

        .mcbx-search-wrapper {
            width: 100%;
            margin: 0px auto 0px auto;
            border-radius: 40px;
            background: #fff;
            border: 2px solid #DDE5ED;
            font-family: inherit;
            font-size: inherit;
            display: flex;
        }

        /* Form text input */

        #mcbx-form input {
            height: 20px;
            padding: 30px 20px;
            font-weight: 600;
            font-size: inherit;
            font-family: inherit !important;
            border: 0;
            background: #fff;
            border-radius: 40px;
            border-top-style: none;
        }

        #mcbx-form input:focus {
            outline: 0;
            background: #fff;
            box-shadow: 0 0 2px rgba(0,0,0,0.8) inset;
        }

        .mcbx-search-wrapper input {
            width: 80%;
            float: left;   
        }
        
        #mcbx-form input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
            color: #DDE5ED !important;
            font-style: normal;
            font-weight: 500 !important;
            font-size: inherit;
            font-family: inherit !important;
        }
        #mcbx-form input::-moz-placeholder { /* Firefox 19+ */
            color: #DDE5ED !important;
            font-style: normal;
            font-weight: 500 !important;
            font-size: inherit;
            font-family: inherit !important;
        }
        #mcbx-form input:-ms-input-placeholder { /* IE 10+ */
            color: #DDE5ED !important;
            font-style: normal;
            font-weight: 500 !important;
            font-size: inherit;
            font-family: inherit !important;
        }
        #mcbx-form input:-moz-placeholder { /* Firefox 18- */
            color: #DDE5ED !important;
            font-style: normal;
            font-weight: 500 !important;
            font-size: inherit;
            font-family: inherit !important;
        }
        .mcbx-name-row {
            padding-top:15px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        .mcbx-name-row {
            transition: height 0.35s ease-in-out;
            overflow: hidden;
        }
        .mcbx-name-row:not(.active) {
            display: none;
        }

        .mcbx-name-row input {
            border: 2px solid #DDE5ED !important;
            width: 49%;
        }

        /* Form submit button */
        .mcbx-search-wrapper button {
            overflow: visible;
            position: relative;
            float: right;
            border: 0;
            padding: 0;
            cursor: pointer;
            height: 60px;
            width: max-content;
            font-weight: 500;
            font-family: inherit !important;
            font-size: inherit !important;
            color: #fff;
            text-transform: uppercase;
            background: #05C3DE;
            border-radius: 40px;
            padding: 10px 20px;
            transition: background ease 0.3s;
        }  

        .mcbx-search-wrapper button:hover{    
            background: #05c3dead;
            transition: background ease 0.3s;
        }  

        .mcbx-search-wrapper button:active,
        .mcbx-search-wrapper button:focus{  
            background: #05A9C7;
            outline: 0;  
        }

        .mcbx-search-wrapper button:focus:before,
        .mcbx-search-wrapper button:active:before{
                border-right-color: #c42f2f;
        }     

        .mcbx-search-wrapper button::-moz-focus-inner { /* remove extra button spacing for Mozilla Firefox */
            border: 0;
            padding: 0;
        }   
        /* Result Message */
        .mcbxResult {
            width: max-content;
            padding: 0 !important;
            background: #fff;
            margin: 10px 0 !important;
            border-radius: 25px;
            border: 1px solid #fff;
            color: #615b5b;
            max-height: 0px;
            overflow:hidden;
        }
        .mcbxResult.success {
            background: #e0fbe0;
            border: 1px solid #dce4dc;
            max-height: 999px;
            overflow: visible;
            padding: 10px 25px !important;
        }
        .mcbxResult.error {
            background: #fbe0e0;
            border: 1px solid #e4dcdd;
            max-height: 999px;
            overflow: visible;
            padding: 10px 25px !important;
        }
    </style>
    <script>
        // When the page is ready
        window.addEventListener("load", function () 
        {
            if (document.querySelector("body") !== null) 
            { 
                // Some element that should be rendered by now before we execute code
                let mcbxCheckExist = setInterval(function() 
                {
                    if (document.getElementById("mcbx-form") !== null) 
                    {
                       
                        // Our element is confirmed to be on the page now!
                        clearInterval(mcbxCheckExist);

                        mcbxAddListenersAndAct();
                    }
                 }, 1500); // check every 1.5 seconds
            }
        });

        function mcbxAddListenersAndAct() 
        {
            // Reveal the names row when they start typing their email
            
            let mcbxEmailInput = document.querySelector(`#mcbx-form input[name="email"]`);
            let mcbxNamesRow = document.querySelector(`#mcbx-form .mcbx-name-row`);
            let mcbxNamesRowRevealed = false;

            mcbxEmailInput.addEventListener("input", function (event) 
            {

                event.preventDefault();

                if(mcbxNamesRowRevealed){return;}   
                
                if (!mcbxNamesRow.classList.contains("active")) 
                {
        
                    mcbxNamesRow.classList.add("active");
                    mcbxNamesRow.style.height = "auto";
        
                    var height = mcbxNamesRow.clientHeight + "px";
        
                    mcbxNamesRow.style.height = "0px";
        
                    setTimeout(function() 
                    {
                        mcbxNamesRow.style.height = height;
                    }, 0);

                    mcbxNamesRowRevealed = true;
        
                } /*else {
        
                    mcbxNamesRow.style.height = "0px";
        
                    mcbxNamesRow.addEventListener("transitionend", function() 
                    {
                        mcbxNamesRow.classList.remove("active");
                    }, 
                    {
                        once: true
                    });
                }*/
            }); 
        }

        // Do things when form is submitted
        let mcbxForm = document.getElementById("mcbx-form");

        mcbxForm.addEventListener("submit", function(e)
        {
            e.preventDefault();

            let mcbxEmailInput = document.querySelector(`#mcbx-form input[name="email"]`);
            let mcbxFirstNameInput = document.querySelector(`#mcbx-form input[name="firstname"]`);
            let mcbxLastNameInput = document.querySelector(`#mcbx-form input[name="lastname"]`);
            let mcbxAudienceName = document.querySelector(`#mcbx-form input[name="audienceName"]`);
            let mcbxTagToApply = document.querySelector(`#mcbx-form input[name="tagToApply"]`);

            promiseAddEmailToList( mcbxEmailInput.value, mcbxFirstNameInput.value, mcbxLastNameInput.value,
                mcbxAudienceName.value, mcbxTagToApply.value )
                .then(data => mcbxDisplayResultMessage(data) );
            
        });

        function mcbxDisplayResultMessage( data ) 
        {

            if(!data.success)
            {
                mcbxResultMessageGenerator("There was a problem with your subscription - please try again", false);
            }

            let responseObj = JSON.parse(data.data);
            if (!("email_address" in responseObj))
            {
                // The key doesn\'t exist
                mcbxResultMessageGenerator("There was a problem with your subscription - please try again", false);
            }
            mcbxResultMessageGenerator("Thank you for your subscription!", true);
        }

        function mcbxResultMessageGenerator( message, success = true )
        {
            let resultText = document.querySelector("p.mcbxResult");

            resultText.classList.remove("success");
            resultText.classList.remove("error");

            if(success)
            {
                resultText.classList.add("success");
            } 
            else
            {
                resultText.classList.add("error");
            }

            resultText.innerHTML = message;
        }
        
        let promiseAddEmailToList = function( email, fname, lname, audienceName, tagToApply ) 
        {
            return new Promise(async function(resolve,reject)
            {
                let formData = new FormData();
                formData.append("email", email);
                formData.append("firstname", fname);
                formData.append("lastname", lname);
                formData.append("audienceName", audienceName);
                formData.append("tagToApply", tagToApply);

                try 
                {
                    let r = await fetch("/email/mcbsubscribe", {method: "POST", body: formData}); 
                    //resolve(r);
                    r.json().then(function(data) 
                    {
                        resolve(data);
                    });
                } 
                catch(e) 
                {
                    //console.log("Huston we have problem...:", e);
                    reject(Error(e));
                }
            });
        }
    </script>