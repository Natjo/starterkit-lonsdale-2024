


document.addEventListener("DOMContentLoaded", (event) => {


 import(`./modules/lenis.js`).then(mod => {
        const lenis = new mod.default({
            lerp: .06
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);
    });

   



});

