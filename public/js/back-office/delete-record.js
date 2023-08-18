$(document).ready(function () {
	$(document).on('click', '.delete-icon', function(){
		if (!confirm('Are you sure you want to delete this record?')) {
	       return false;
	    }
	});
});