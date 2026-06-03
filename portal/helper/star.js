function setStarColorsForSelected(star) {
  var name = star.getAttribute('name');

  Array.prototype.forEach.call(document.querySelectorAll('input[name="'+name+'"]'), function(e) {
    var label = document.querySelector('label[for='+e.id+']');
    if(e.value <= star.value) {
      label.style.color = "#B22625";
    } else {
      label.style.color = "#ccc";
    }
  });
}

var labels = document.getElementsByClassName('star_label');
var inputs = document.getElementsByClassName('rating');
function resetStars() {
  Array.prototype.forEach.call(labels, function(e) {
    e.style.color = '#ccc';
  });
}

Array.prototype.forEach.call(labels, function(e) {
  e.addEventListener('mouseleave',function() {
    var name = document.getElementById(e.getAttribute('for')).getAttribute('name'); // name of associated input
    var origin = document.querySelector('input[name="'+name+'"]:checked');
    if(origin === null) {
      resetStars();
    } else {
      setStarColorsForSelected(origin);
    }
  });
});

Array.prototype.forEach.call(labels, function(e) {
  e.addEventListener('mouseenter',function() {
    var origin = document.getElementById(e.getAttribute('for'));
    setStarColorsForSelected(origin);
  });
});

Array.prototype.forEach.call(inputs, function() {
  var origin = document.querySelector('input[name="rate"]:checked');
  if(origin !== null) {
    setStarColorsForSelected(origin);
  }
});
