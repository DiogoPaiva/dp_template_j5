document.addEventListener("DOMContentLoaded", function () {
    const wrappers = document.querySelectorAll('.musica_wrapper');

    // Function to collapse a musica section
    function collapseMusica(musica) {
        musica.classList.add('collapsed');
        musica.style.maxHeight = null;
    }

    // Function to expand a musica section
    function expandMusica(musica) {
        musica.classList.remove('collapsed');
        musica.style.maxHeight = musica.scrollHeight + 'px';
    }

    // Add click event to each slide title
    wrappers.forEach(wrapper => {
        const title = wrapper.querySelector('.slide-title');
        const musica = wrapper.querySelector('.musica');

        if (!title || !musica) return;

        title.addEventListener('click', function () {
            if (musica.classList.contains('collapsed')) {
                // Collapse all other items
                wrappers.forEach(w => {
                    const m = w.querySelector('.musica');
                    if (m && m !== musica) {
                        collapseMusica(m);
                    }
                });
                // Expand clicked item
                expandMusica(musica);
            } else {
                // Collapse clicked item
                collapseMusica(musica);
            }
        });
    });

    // Auto-open only the first item
    if (wrappers.length > 0) {
        const firstMusica = wrappers[0].querySelector('.musica');
        if (firstMusica) {
            // Collapse all first
            wrappers.forEach(wrapper => {
                const musica = wrapper.querySelector('.musica');
                if (musica) {
                    collapseMusica(musica);
                }
            });
            // Then open the first
            expandMusica(firstMusica);
        }
    }
});