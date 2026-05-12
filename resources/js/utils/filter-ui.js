export function handleFilterBarPosition() {
    const $filterBar = $('.artworks-filter-section');
    const $hero = $('#marketplace-sub-header');

    if (!$filterBar.length || !$hero.length) return;

    const filterRect = $filterBar[0].getBoundingClientRect();
    const heroRect = $hero[0].getBoundingClientRect();
    console.log(filterRect,' - ',heroRect)

    // When filter bar reaches bottom of hero section
    if (filterRect.top <= heroRect.bottom) {
        $filterBar.addClass('position-top');
    } else {
        $filterBar.removeClass('position-top');
    }
}