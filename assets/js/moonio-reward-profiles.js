(() => {
    const dialog = document.getElementById('rewardProfile');
    const content = document.getElementById('rewardProfileContent');
    const ranking = document.querySelector('.moon-ranking');
    const base = new URL(ranking.dataset.baseUrl, location.origin);
    let request, opener;
    function element(tag, className, text) {
        const node = document.createElement(tag);
        node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    }
    function render(profile) {
        const identity = element('div', 'moon-reward-identity');
        const photo = element('img', 'moon-reward-photo');
        photo.alt = 'Foto de ' + profile.name;
        photo.onerror = () => { photo.onerror = null; photo.src = new URL('assets/img/moonio-mark.svg', base).href; };
        photo.src = new URL(profile.profile_picture || 'assets/img/moonio-mark.svg', base).href;
        const details = element('div', 'moon-reward-person');
        details.append(element('h3', 'moon-reward-name', profile.name), element('p', 'moon-reward-division', profile.division));
        identity.append(photo, details);
        const points = element('p', 'moon-reward-total');
        points.append(element('strong', '', String(profile.points)), document.createTextNode(' puntos totales'));
        const title = element('h3', 'moon-earned-title', `Recompensas obtenidas (${profile.trophies.length})`);
        const trophies = element('div', 'moon-earned-grid');
        profile.trophies.forEach(trophy => {
            const card = element('article', 'moon-earned-trophy');
            const icon = element('span', 'moon-earned-icon', trophy.icon);
            icon.setAttribute('aria-hidden', 'true');
            card.append(icon, element('h4', '', trophy.name), element('p', '', trophy.description));
            const unit = {points:'puntos', messages:'mensajes'}[trophy.requirement_type];
            if (unit) card.append(element('small', 'moon-earned-requirement', `Requisito: ${trophy.requirement_value} ${unit}`));
            trophies.append(card);
        });
        if (!profile.trophies.length) trophies.append(element('p', 'moon-reward-empty', 'Este usuario aún no ha obtenido trofeos.'));
        content.replaceChildren(identity, points, title, trophies);
    }
    ranking.addEventListener('click', async event => {
        const button = event.target.closest('[data-profile-url]');
        if (!button) return;
        request?.abort();
        const currentRequest = new AbortController(); request = currentRequest; opener = button;
        content.replaceChildren(element('p', 'moon-reward-loading', 'Cargando recompensas…'));
        content.setAttribute('aria-busy', 'true');
        if (!dialog.open) dialog.showModal();
        try {
            const response = await fetch(button.dataset.profileUrl, {signal:currentRequest.signal});
            if (!response.ok) throw new Error(response.status === 401 ? 'Inicia sesión para ver las recompensas.' : 'No se pudo cargar este perfil. Inténtalo de nuevo.');
            const profile = await response.json();
            if (!currentRequest.signal.aborted) render(profile);
        } catch (error) {
            if (error.name !== 'AbortError') content.replaceChildren(element('p', 'moon-reward-error', error.message));
        } finally {
            if (request === currentRequest) content.setAttribute('aria-busy', 'false');
        }
    });
    dialog.querySelector('.moon-reward-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('close', () => { request?.abort(); opener?.focus(); });
    dialog.addEventListener('click', event => {
        const box = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom)) dialog.close();
    });
})();
