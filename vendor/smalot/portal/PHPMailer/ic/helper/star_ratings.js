function setStarColorsForSelected(star) {
  var name = star.attr('name');

  $('input[name='+name+']').each(function() {
    var label = $('label[for='+this.id+']');
    if(this.value <= star.val()) {
      label.css('color', "#B22625");
    } else {
      label.css('color', "#ccc");
    }
  });
}

function resetStars() {
  $('.star_label').each(function() {
    $(this).css('color','#ccc');
  });
}

$('#candidates').on('mouseleave','.star_label',function() {
  var name = $('#'+$(this).attr('for')).attr('name'); // name of associated input
  var origin = $('input[name='+name+']:checked');
  if(origin.length == 0) {
    resetStars();
  } else {
    setStarColorsForSelected(origin);
  }
});

$('#candidates').on('mouseenter','.star_label',function() {
  var origin = $('#'+$(this).attr('for'));

  setStarColorsForSelected(origin);
});

$(document).ready(function() {
  $('.rating').each(function() {
    var origin = $('input[name=rate]:checked');
    setStarColorsForSelected(origin);
  });
});
