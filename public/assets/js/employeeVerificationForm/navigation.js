const content = document.getElementById("content");
const styleLink = document.getElementById("dynamic-styles");

function loadPage(page, cssFile, jsFile) {
    console.log(ROOT);
    fetch(`${ROOT}/app/views/workerVerification/${page}.view.php`)
        .then((response) => {
            if (!response.ok) throw new Error("Page not found");
            return response.text();
        })
        .then((html) => {
            content.innerHTML = html;
            styleLink.href = `${ROOT}/public/assets/css/employeeVerificationForm/${cssFile}.css`;
            loadScript(jsFile);
        })
        .catch((err) => console.error(err));
}

function loadScript(jsFile) {
    const script = document.createElement("script");
    script.src = `${ROOT}/public/assets/js/employeeVerificationForm/${jsFile}.js`;
    script.type = "text/javascript";

    script.onload = () => {
        console.log(`${jsFile} loaded successfully!`);
        addEventListeners(jsFile);
    };

    script.onerror = (error) => {
        console.error(`Failed to load script: ${jsFile}`, error);
    };

    document.body.appendChild(script);
}

function addEventListeners(page) {
    document.querySelectorAll(".page-nav").forEach((el) => {
        el.removeEventListener("click", navigate);
    });

    if (page === "page1") {
        restoreFormData();
        document.getElementById("next1").addEventListener("click", (event) => {
            event.preventDefault();
            // Check if validateForm exists and run validation before navigation
            if (typeof window.validateForm === "function") {
                if (window.validateForm()) {
                    saveFormData();
                    loadPage("page2", "page2", "page2");
                } else {
                    console.log("Form validation failed");
                }
            } else {
                console.error("Validation function not found");
            }
        });
    } else if (page === "page2") {
        document.getElementById("back2").addEventListener("click", (event) => {
            event.preventDefault();
            restoreFormData();
            loadPage("page1", "page1", "page1");
        });
        document.getElementById("next2").addEventListener("click", (event) => {
            event.preventDefault();
            if (typeof window.validateForm === "function") {
                if (window.validateForm()) {
                    loadPage("page3", "page3", "page3");
                } else {
                    console.log("Form validation failed");
                }
            } else {
                console.error("Validation function not found");
            }
        });
    } else if (page === "page3") {
        document.getElementById("back3").addEventListener("click", (event) => {
            event.preventDefault();
            loadPage("page2", "page2", "page2");
        });
        document.getElementById("submit").addEventListener("click", (event) => {
            event.preventDefault();
            if (typeof window.validateForm === "function") {
                if (window.validateForm()) {
                    alert("Form Submitted Successfully!");
                } else {
                    console.log("Form validation failed");
                }
            } else {
                console.error("Validation function not found");
            }
        });
    }
}

// Initial page load
loadPage("page1", "page1", "page1");
