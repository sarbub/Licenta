import { ValidateUserData } from './validation.js';

class ValidateUserInfo {
    constructor() {
        // Initially, DOM elements are not passed; they are initialized later
        this.emailInputValue = null;
        this.emailInputError = null;
        this.passwordInputValue = null;
        this.passwordInputError = null;
        this.submitButton = null;
        this.validator = null;
        this.userForm = null;
    }

    MainAction() {
        // Select DOM items
        this.emailInputValue = document.querySelector("#email");
        this.emailInputError = document.querySelector("#emailError");
        this.passwordInputValue = document.querySelector("#password");
        this.passwordInputError = document.querySelector("#passwordError");

        this.userForm = document.querySelector("#loginForm"); // Fixed form ID
        this.submitButton = document.querySelector("#loginButton");

        this.validator = new ValidateUserData();

        // Add event listeners to buttons
        this.submitButton.addEventListener("click", (event) => {
            event.preventDefault();
            let isValid = true;

            // Corrected validation checks
            // Corrected validation checks
            if (!this.validator.ValidateEmail(this.emailInputValue.value, this.emailInputError)) {
                isValid = false;
            }

            if (!this.validator.ValidatePasswords(this.passwordInputValue.value, this.passwordInputError)) {
                isValid = false;
            }

            if (!isValid) {
                console.log("There are some errors");
                return false;
            }

            // If valid, submit the form
            console.log("Form is valid, submitting...");
            this.userForm.submit(); // Manually trigger form submission
        });
    }
}


class Actions {
    constructor() {
        this.loginButton = null;
        this.loginDiv = null;
    }

    main() {
        this.loginButton = document.getElementById("loginLButton");
       
    }
    
    text_animation() {
        document.addEventListener("DOMContentLoaded", () => {
            const textElements = document.querySelectorAll(".text_animation"); // Select all elements
            if (textElements.length === 0) {
                console.error("No elements with class 'text_animation' found.");
                return; // Exit if no elements are found
            }
    
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate"); // Add the class to the intersecting element
                        observer.unobserve(entry.target); // Trigger once and stop observing
                    }
                });
            }, { threshold: 0.1 }); // Adjust threshold as needed
    
            textElements.forEach(textElement => {
                observer.observe(textElement); // Observe each element
            });
        });
    }
    

    fade_text_animation() {
        document.addEventListener("DOMContentLoaded", () => {
            const textElements = document.querySelectorAll(".fade_scale_animation"); // Select all elements
            if (textElements.length === 0) {
                console.error("No elements with class 'fade_scale_animation' found.");
                return; // Exit if no elements are found
            }
    
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate"); // Add the class to the intersecting element
                        observer.unobserve(entry.target); // Trigger once and stop observing
                    }
                });
            }, { rootMargin: '0px 0px -20% 0px'}); // Adjust threshold as needed
    
            textElements.forEach(textElement => {
                observer.observe(textElement); // Observe each element
            });
        });
    }

    bounce_animation(){
        document.addEventListener("DOMContentLoaded", () => {
            const bounceDivs = document.querySelectorAll(".bounce_animation"); // Select all divs with the class
        
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate"); // Add class to start animation
                        observer.unobserve(entry.target); // Stop observing once it has animated
                    }
                });
            }, { threshold: 0.5 }); // Adjust threshold for when the animation should trigger
        
            bounceDivs.forEach(div => observer.observe(div)); // Observe each div
        });

    }

    rotate_animation(){
        document.addEventListener("DOMContentLoaded", () => {
            const rotateElements = document.querySelectorAll(".rotate_animation"); // Select all elements with the class
        
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate"); // Add class to start animation
                        observer.unobserve(entry.target); // Stop observing once it has animated
                    }
                });
            }, { threshold: 0.5 }); // Adjust threshold for when the animation should trigger
        
            rotateElements.forEach(element => observer.observe(element)); // Observe each element
        });
        
    }

    popAnimation() {
        document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll(".pop_animation");
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate"); // Add the animation class
                        observer.unobserve(entry.target); // Stop observing once animated
                    }
                });
            }, { threshold: 0.5 }); // Trigger when halfway in view
    
            cards.forEach(card => {
                observer.observe(card); // Observe each card
            });
        });
    }
    
    
    


}


var ValidateInformation = new ValidateUserInfo();
var action = new Actions();

action.main();
action.text_animation();
action.fade_text_animation();
action.bounce_animation();
action.rotate_animation();
action.popAnimation();
// ValidateInformation.MainAction();
