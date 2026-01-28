document.addEventListener('DOMContentLoaded', function () {
    const articles = document.querySelectorAll('article');
    const tagContainer = document.getElementById('tag-filter-container');

    if (!tagContainer) return; // Exit if not on blog page

    const tags = new Set();

    // Extract tags from articles
    articles.forEach(article => {
        const badges = article.querySelectorAll('.badge');
        badges.forEach(badge => {
            // Clean up tag text (remove # if present, though Hugo template adds it)
            const tagText = badge.textContent.trim();
            tags.add(tagText);
            // Add click event to badges inside articles
            badge.style.cursor = 'pointer';
            badge.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent article click if any
                filterByTag(tagText);
            });
        });
    });

    // Create "All" button
    const allBtn = document.createElement('button');
    allBtn.className = 'btn btn-outline-primary btn-sm active filter-btn me-2 mb-2';
    allBtn.textContent = 'Ver todos';
    allBtn.addEventListener('click', () => filterByTag('all'));
    tagContainer.appendChild(allBtn);

    // Create button for each tag
    Array.from(tags).sort().forEach(tag => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-outline-secondary btn-sm filter-btn me-2 mb-2';
        btn.textContent = tag;
        btn.addEventListener('click', () => filterByTag(tag));
        tagContainer.appendChild(btn);
    });

    function filterByTag(selectedTag) {
        // Update button states
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.textContent === selectedTag || (selectedTag === 'all' && btn.textContent === 'Ver todos')) {
                btn.classList.add('active');
                if (selectedTag === 'all') {
                    btn.classList.replace('btn-outline-primary', 'btn-primary');
                } else {
                    btn.classList.replace('btn-outline-secondary', 'btn-secondary');
                }
            } else {
                btn.classList.remove('active');
                btn.classList.replace('btn-primary', 'btn-outline-primary');
                btn.classList.replace('btn-secondary', 'btn-outline-secondary');
            }
        });

        // Update badge highlights
        document.querySelectorAll('.badge').forEach(badge => {
            if (badge.textContent.trim() === selectedTag) {
                badge.classList.remove('bg-light', 'text-secondary');
                badge.classList.add('bg-primary', 'text-white');
            } else {
                badge.classList.remove('bg-primary', 'text-white');
                badge.classList.add('bg-light', 'text-secondary');
            }
        });

        // Filter articles
        articles.forEach(article => {
            if (selectedTag === 'all') {
                article.classList.remove('d-none');
            } else {
                const articleTags = Array.from(article.querySelectorAll('.badge')).map(b => b.textContent.trim());
                if (articleTags.includes(selectedTag)) {
                    article.classList.remove('d-none');
                } else {
                    article.classList.add('d-none');
                }
            }
        });
    }
});
