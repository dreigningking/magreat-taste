/**
 * Theme JavaScript
 * Combined scripts for the Wonegig platform
 */

function initializeWonegigTheme() {
    // Add a cleanup function at the start
    function cleanup() {
        // Remove all existing document click handlers
        document.removeEventListener('click', documentClickHandler);
        
        // Clean up dropdown listeners
        const dropdowns = [
            { button: 'userMenuButton', dropdown: 'userDropdown' },
            { button: 'notificationButton', dropdown: 'notificationDropdown' },
            { button: 'categoryFilterBtn', dropdown: 'categoryFilterDropdown' },
            { button: 'priceFilterBtn', dropdown: 'priceFilterDropdown' },
            { button: 'timeFilterBtn', dropdown: 'timeFilterDropdown' },
            { button: 'sortFilterBtn', dropdown: 'sortFilterDropdown' }
        ];

        dropdowns.forEach(item => {
            const button = getElement(item.button);
            if (button) {
                button.replaceWith(button.cloneNode(true));
            }
        });
    }

    // Document click handler as a named function
    function documentClickHandler(e) {
        const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
        dropdowns.forEach(dropdown => {
            const button = document.querySelector(`[id$="Button"][aria-controls="${dropdown.id}"]`);
            if (dropdown && !dropdown.contains(e.target) && button && !button.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Clean up before initializing
    cleanup();

    // Modified setupDropdown function
    function setupDropdown(buttonId, dropdownId, closeOthers = []) {
        const button = getElement(buttonId);
        const dropdown = getElement(dropdownId);

        if (button && dropdown) {
            // Use a new button clone to ensure clean event listeners
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', function(e) {
                e.stopPropagation();
                closeOthers.forEach(otherId => {
                    const otherDropdown = getElement(otherId);
                    if (otherDropdown) otherDropdown.classList.add('hidden');
                });
                dropdown.classList.toggle('hidden');
            });
        }
    }

    // Add this function after setupDropdown
    function setupOutsideClickHandler(buttonId, dropdownId) {
        const button = getElement(buttonId);
        const dropdown = getElement(dropdownId);

        if (button && dropdown) {
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    }

    // Add document click handler once
    document.addEventListener('click', documentClickHandler);

    /**
     * UTILITY FUNCTIONS
     */
    function getElement(id) {
        return document.getElementById(id);
    }

    function elementExists(...ids) {
        return ids.every(id => getElement(id) !== null);
    }

    /**
     * RANGE SLIDERS
     */
    function setupRangeSliders() {
        const sliders = [
            { slider: 'mobileRangeSlider', value: 'mobileRangeValue' },
            { slider: 'desktopRangeSlider', value: 'desktopRangeValue' }
        ];

        sliders.forEach(item => {
            const slider = getElement(item.slider);
            const value = getElement(item.value);
            if (slider && value) {
                value.textContent = slider.value;
                slider.addEventListener('input', function () {
                    value.textContent = slider.value;
                });
            }
        });
    }

    /**
     * MOBILE SEARCH AND FILTERS
     */
    function setupMobileToggles() {
        const toggles = [
            { toggle: 'searchToggle', container: 'searchContainer', otherContainer: 'filterContainer' },
            { toggle: 'filterToggle', container: 'filterContainer', otherContainer: 'searchContainer' }
        ];

        toggles.forEach(item => {
            const toggle = getElement(item.toggle);
            const container = getElement(item.container);
            const otherContainer = getElement(item.otherContainer);

            if (toggle && container) {
                toggle.addEventListener('click', function () {
                    container.classList.toggle('expanded');
                    if (otherContainer) otherContainer.classList.remove('expanded');
                });
            }
        });
    }

    /**
     * MOBILE SIDEBAR
     */
    function setupMobileSidebar() {
        const mobileMenuButton = getElement('mobileMenuButton');
        const mobileSidebar = getElement('mobileSidebar');
        const sidebarBackdrop = getElement('sidebarBackdrop');
        const closeSidebar = getElement('closeSidebar');

        function openSidebar() {
            if (mobileSidebar) mobileSidebar.classList.add('active');
            if (sidebarBackdrop) sidebarBackdrop.classList.add('active');
        }

        function closeSidebarMenu() {
            if (mobileSidebar) mobileSidebar.classList.remove('active');
            if (sidebarBackdrop) sidebarBackdrop.classList.remove('active');
        }

        if (mobileMenuButton && mobileSidebar) {
            mobileMenuButton.addEventListener('click', openSidebar);
        }

        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarMenu);
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', closeSidebarMenu);
        }
    }

    /**
     * MODALS
     */
    function setupModals() {
        const jobModal = getElement('jobModal');
        const closeModal = getElement('closeModal');

        if (jobModal && closeModal) {
            closeModal.addEventListener('click', function () {
                jobModal.classList.add('hidden');
            });
            jobModal.addEventListener('click', function (e) {
                if (e.target === jobModal) {
                    jobModal.classList.add('hidden');
                }
            });
        }
    }

    /**
     * DROPDOWNS (User menu and Notifications)
     */
    function setupAllDropdowns() {
        const dropdowns = [
            {
                button: 'userMenuButton',
                dropdown: 'userDropdown',
                closeOthers: ['notificationDropdown']
            },
            {
                button: 'notificationButton',
                dropdown: 'notificationDropdown',
                closeOthers: ['userDropdown']
            }
        ];

        dropdowns.forEach(item => {
            setupDropdown(item.button, item.dropdown, item.closeOthers);
            setupOutsideClickHandler(item.button, item.dropdown);
        });
    }

    /**
     * TAB SWITCHING
     */
    function setupTabs() {
        const tabButtons = document.querySelectorAll('.tab-button');
        if (tabButtons.length > 0) {
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    tabButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const tabTarget = btn.getAttribute('data-tab-target');
                    if (tabTarget) {
                        document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
                        const targetContent = document.getElementById(tabTarget);
                        if (targetContent) targetContent.classList.remove('hidden');
                    }
                });
            });
        }
    }

    /**
     * FILTER DROPDOWNS
     */
    function setupFilterDropdowns() {
        const filterDropdowns = [
            { button: 'categoryFilterBtn', dropdown: 'categoryFilterDropdown' },
            { button: 'priceFilterBtn', dropdown: 'priceFilterDropdown' },
            { button: 'timeFilterBtn', dropdown: 'timeFilterDropdown' },
            { button: 'sortFilterBtn', dropdown: 'sortFilterDropdown' }
        ];

        filterDropdowns.forEach(item => {
            setupDropdown(item.button, item.dropdown);
            setupOutsideClickHandler(item.button, item.dropdown);
        });

        // Close all filter dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            filterDropdowns.forEach(item => {
                const dropdown = getElement(item.dropdown);
                const button = getElement(item.button);
                if (dropdown && !dropdown.contains(e.target) && button && !button.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    }

    /**
     * MOBILE FILTER MODAL
     */
    function setupMobileFilterModal() {
        const mobileFilterBtn = getElement('mobileFilterBtn');
        const mobileFilterModal = getElement('mobileFilterModal');
        const closeFilterModal = getElement('closeFilterModal');

        if (elementExists('mobileFilterBtn', 'mobileFilterModal', 'closeFilterModal')) {
            mobileFilterBtn.addEventListener('click', function () {
                mobileFilterModal.classList.remove('hidden');
            });
            closeFilterModal.addEventListener('click', function () {
                mobileFilterModal.classList.add('hidden');
            });
            mobileFilterModal.addEventListener('click', function (e) {
                if (e.target === mobileFilterModal) {
                    mobileFilterModal.classList.add('hidden');
                }
            });
        }
    }

    /**
     * JOB CAROUSEL
     */
    function setupJobCarousel() {
        // Placeholder for job carousel logic (e.g., Swiper, Slick, etc.)
    }

    /**
     * COUNTER ANIMATIONS
     */
    function setupCounterAnimations() {
        // Placeholder for counter animation logic
    }

    /**
     * POST JOB SCRIPTS
     */
    function setupSkillsInput() {
        // Placeholder for skills input logic
    }

    function setupSalaryRange() {
        // Placeholder for salary range logic
    }

    function setupPeopleCounter() {
        // Placeholder for people counter logic
    }

    function setupFormSteps() {
        // Placeholder for form steps logic
    }

    function setupPaymentMethod() {
        // Placeholder for payment method logic
    }

    function setupCardFormat() {
        // Placeholder for card format logic
    }

    function setupMonitoringType() {
        // Placeholder for monitoring type logic
    }

    function setupCouponCode() {
        // Placeholder for coupon code logic
    }

    // DASHBOARD SIDEBAR TOGGLE
    function setupDashboardSidebarToggle() {
        window.toggleDashboardSidebar = function() {
            const sidebar = document.getElementById('dashboardSidebar');
            const main = document.getElementById('dashboard-main');
            if (!sidebar || !main) return;
            if (window.innerWidth < 1024) {
                // Mobile: slide in/out as overlay
                sidebar.classList.toggle('-translate-x-full');
            } else {
                // Desktop: push/rescale main content
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    main.classList.add('ml-64');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    main.classList.remove('ml-64');
                }
            }
        };
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('dashboardSidebar');
            const main = document.getElementById('dashboard-main');
            if (!sidebar || !main) return;
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                main.classList.remove('ml-64');
            }
        });
    }

    // Call all setup functions
    setupRangeSliders();
    setupMobileToggles();
    setupMobileSidebar();
    setupModals();
    setupAllDropdowns();
    setupTabs();
    setupFilterDropdowns();
    setupMobileFilterModal();
    setupJobCarousel();
    setupCounterAnimations();
    setupSkillsInput();
    setupSalaryRange();
    setupPeopleCounter();
    setupPaymentMethod();
    setupCardFormat();
    setupMonitoringType();
    setupCouponCode();
    setupDashboardSidebarToggle();
}

// Use only livewire:navigated for both initial load and navigation
document.addEventListener('livewire:navigated', initializeWonegigTheme);