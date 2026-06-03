<iframe src="external_form_url"></iframe>
<script>
  // Assuming the iframe is loaded from the same domain as your main page
  document.querySelector('iframe').addEventListener('load', function() {
    // Assuming the form has an ID 'application-form' and the submit button has an ID 'submit-button'
    var iframeDocument = this.contentDocument || this.contentWindow.document;
    var form = iframeDocument.getElementById('application-form');
    var submitButton = iframeDocument.getElementById('submit-button');

    submitButton.addEventListener('click', function() {
      // Perform any additional actions if needed, such as form validation
      // Redirect the user to your desired page after form submission
      window.location.href = 'https://yourserver.com/thank-you-page';
    });
  });
</script>


<iframe src="external_form_url"></iframe>
<script>
  document.querySelector('iframe').addEventListener('load', function() {
    var iframeDocument = this.contentDocument || this.contentWindow.document;
    var form = iframeDocument.querySelector('form'); // Select the first <form> element

    form.addEventListener('submit', function(event) {
      // Prevent the default form submission behavior
      event.preventDefault();

      // Perform any additional actions if needed, such as form validation

      // Redirect the user to your desired page after form submission
      window.location.href = 'https://yourserver.com/thank-you-page';
    });
  });
</script>
