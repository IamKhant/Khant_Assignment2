// Back-to-top button functionality
window.onscroll = function () {
  var backToTopButton = document.getElementById("backToTop");
  if (document.body.scrollTop > 110 || document.documentElement.scrollTop > 110) {
    backToTopButton.style.display = "block"; // Show button
  } else {
    backToTopButton.style.display = "none"; // Hide button
  }
};

// Smooth scroll to top when the button is clicked
document.getElementById("backToTop").addEventListener("click", function (event) {
  event.preventDefault(); // Prevent default anchor click behavior
  window.scrollTo({ top: 0, behavior: 'smooth' }); // Smooth scroll to top
});


// Home background image rotation
const homeBackgroundImages = [
  'img/homeBG.JPG',
  'img/background2.JPG',
  'img/background3.JPG',
];

function changeHomeBackgroundImage() {
  const randomIndex = Math.floor(Math.random() * homeBackgroundImages.length);
  const backgroundImage = document.getElementById('backgroundImage');
  backgroundImage.src = homeBackgroundImages[randomIndex];
}
changeHomeBackgroundImage();
setInterval(changeHomeBackgroundImage, 2000);

// Hamburger menu functionality
function toggleMenu() {
  var middleNav = document.querySelector('.middle_nav');
  middleNav.classList.toggle('active');
}


// Arrow hiding functionality when scrolling
window.addEventListener('scroll', function () {
  var arrow = document.getElementById('scrollArrow');
  if (window.scrollY > 50) { // Adjust scrollY value as needed
    arrow.style.display = 'none';
  } else {
    arrow.style.display = 'block';
  }
});


// Random images for the first plant section
const plantImages1 = [
  "img/contributeImg/Burseraceae_family1.JPG",
  "img/contributeImg/Canariumalbum.jpg",
  "img/contributeImg/Burseraceae_family2.jpeg",
  "img/contributeImg/BoswelliaSamhaensis.jpeg",
  "img/contributeImg/Dacryodes_genus1.jpeg",
  "img/contributeImg/Dacryodes_genus2.jpg",
  "img/contributeImg/CanariumGenus2.jpg"
];

// Random images for the second plant section
const plantImages2 = [
  "img/contributeImg/Santiria_apiculata.jpg",
  "img/contributeImg/MexicanFrankincense.jpeg",
  "img/contributeImg/BurseraceaeFamily2.jpg",
  "img/contributeImg/BoswelliaAmeero.jpeg",
  "img/contributeImg/CommonHedgeParsley.jpg",
  "img/contributeImg/CommonHedgeParsley2.jpeg",
  "img/contributeImg/Scrub_Turpentine.jpg"
];

// Function to get a random image from the first array
function getRandomPlantImage1() {
  return plantImages1[Math.floor(Math.random() * plantImages1.length)];
}

// Function to get a random image from the second array
function getRandomPlantImage2(excludeImage) {
  let randomImage;
  do {
    randomImage = plantImages2[Math.floor(Math.random() * plantImages2.length)];
  } while (randomImage === excludeImage); // Ensure it doesn't match the first image
  return randomImage;
}

// Function to update images
function updateImages() {
  const firstImage = getRandomPlantImage1();
  const secondImage = getRandomPlantImage2(firstImage);

  document.getElementById('image1').src = firstImage;
  document.getElementById('image2').src = secondImage;
}

// Initial image setup
updateImages();

// Change images every 2 seconds (2000 milliseconds)
setInterval(updateImages, 2000);


function resetForm() {
    // Get the form element by its ID
    const form = document.getElementById('addPlantForm');
    
    // Reset text inputs to empty or default values
    form.scientific_name.value = ''; // or set to a specific default
    form.common_name.value = '';     // repeat for each input
    form.family.value = '';
    form.genus.value = '';
    form.species.value = '';
    
    // Clear file inputs by resetting them to an empty string
    form.plant_image.value = '';
    form.description.value = '';
    
    // Reset any error messages or custom validation feedback
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => error.innerText = '');
    
    // Optionally, if the form has native validation, reset it
    form.reset();
    
    console.log('Form has been reset');
}
function resetregisterForm() {
    // Get the form element by its ID
    const form = document.getElementById('registrationForm');
    
    // Reset text inputs to empty or default values
    form.first_name.value = '';
    form.last_name.value = '';
    form.student_id.value = '';
    form.dob.value = '';
    form.email.value = '';
    form.hometown.value = '';
    form.contact_number.value = '';
    form.password.value = '';
    form.confirm_password.value = '';
    
    // Reset gender radio buttons to default (Female in this case)
    form.gender_male.checked = false;
    form.gender_female.checked = true; // Set Female as the default checked option

    // Clear error messages, if any
    const errorMessages = document.querySelectorAll('.text-danger');
    errorMessages.forEach(error => error.innerText = '');
    
    // Optionally, if form has native validation, reset it
    form.reset();

    console.log('Registration form has been reset');
}