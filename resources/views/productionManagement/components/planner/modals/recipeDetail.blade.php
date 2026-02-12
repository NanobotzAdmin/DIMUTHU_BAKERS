<!-- Recipe Detail Modal -->
<div id="recipe-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0" id="recipe-modal-backdrop">
    </div>

    <!-- Modal Panel Container -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div id="recipe-modal-content"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all scale-95 opacity-0 sm:my-8 sm:w-full sm:max-w-4xl">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 id="info-name" class="text-2xl font-semibold text-gray-900 mb-2"></h3>
                            <p id="info-desc" class="text-sm text-gray-600"></p>
                        </div>
                        <button onclick="RecipeModal.close()"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none p-1 rounded-md hover:bg-gray-100">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Badges Row -->
                    <div class="flex items-center gap-2 flex-wrap mt-3" id="info-badges">
                        <!-- Injected via JS -->
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto">

                    <!-- Time Breakdown -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center gap-2 text-blue-700 mb-1">
                                <i data-lucide="timer" class="w-4 h-4"></i>
                                <span class="text-sm font-medium">Production</span>
                            </div>
                            <div class="text-2xl font-bold text-blue-900" id="info-time-prod"></div>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-4 border border-amber-200" id="box-rest-time">
                            <div class="flex items-center gap-2 text-amber-700 mb-1">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                <span class="text-sm font-medium">Rest/Proof</span>
                            </div>
                            <div class="text-2xl font-bold text-amber-900" id="info-time-rest"></div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center gap-2 text-green-700 mb-1">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                <span class="text-sm font-medium">Shelf Life</span>
                            </div>
                            <div class="text-2xl font-bold text-green-900" id="info-shelf-life"></div>
                        </div>
                    </div>

                    <!-- Dependencies Warning -->
                    <div id="info-dependencies-container"
                        class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="font-medium text-yellow-900 mb-2">Dependencies Required</h4>
                                <div class="space-y-2" id="info-dependencies-list"></div>
                                <p class="text-xs text-yellow-700 mt-2">These items must be completed before starting
                                    this recipe</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ingredients -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
                            Ingredients
                            <span
                                class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground"
                                id="count-ingredients">0</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2" id="info-ingredients-list">
                            <!-- Injected -->
                        </div>
                    </div>

                    <!-- Equipment -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="chef-hat" class="w-5 h-5 text-gray-600"></i>
                            Equipment Required
                            <span
                                class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground"
                                id="count-equipment">0</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="info-equipment-list">
                            <!-- Injected -->
                        </div>
                    </div>

                    <!-- Production Steps -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="timer" class="w-5 h-5 text-gray-600"></i>
                            Production Steps
                            <span
                                class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground"
                                id="count-steps">0 steps</span>
                        </h3>
                        <div class="space-y-2" id="info-steps-list">
                            <!-- Injected -->
                        </div>
                    </div>

                    <!-- Allergens & Tags -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div id="section-allergens">
                            <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                                Allergens
                            </h4>
                            <div class="flex flex-wrap gap-2" id="info-allergens-list"></div>
                        </div>

                        <div id="section-tags">
                            <h4 class="font-medium text-gray-900 mb-2">Tags</h4>
                            <div class="flex flex-wrap gap-2" id="info-tags-list"></div>
                        </div>
                    </div>

                    <!-- Costing -->
                    <div id="info-costing"
                        class="hidden bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-medium text-green-900 mb-3">Cost Breakdown</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <div class="text-xs text-green-700">Materials</div>
                                <div class="text-lg font-bold text-green-900" id="cost-materials"></div>
                            </div>
                            <div>
                                <div class="text-xs text-green-700">Labor</div>
                                <div class="text-lg font-bold text-green-900" id="cost-labor"></div>
                            </div>
                            <div>
                                <div class="text-xs text-green-700">Overhead</div>
                                <div class="text-lg font-bold text-green-900" id="cost-overhead"></div>
                            </div>
                            <div>
                                <div class="text-xs text-green-700">Total Cost</div>
                                <div class="text-xl font-bold text-green-900" id="cost-total"></div>
                            </div>
                        </div>
                        <div class="text-xs text-green-700 mt-2" id="cost-per-unit"></div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 flex flex-row-reverse gap-3 rounded-b-lg border-t bg-gray-50/50">
                    <button type="button" onclick="RecipeModal.addToTimeline()"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-[#D4A017] text-white hover:bg-[#B8860B] h-10 px-4 py-2">
                        <i data-lucide="package" class="w-4 h-4 mr-2"></i>
                        Add to Timeline
                    </button>
                    <button type="button" onclick="RecipeModal.close()"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const RecipeModal = {
        currentRecipe: null,
        onAddCallback: null,

        open: function (recipe, onAddCallback) {
            this.currentRecipe = recipe;
            this.onAddCallback = onAddCallback;
            const modal = document.getElementById('recipe-modal');
            const backdrop = document.getElementById('recipe-modal-backdrop');
            const content = document.getElementById('recipe-modal-content');

            // Populate Basic Info
            document.getElementById('info-name').innerText = recipe.name;
            document.getElementById('info-desc').innerText = recipe.description || '';

            // --- Badges ---
            let badgesHtml = '';
            // Category
            badgesHtml += this.renderBadge(recipe.category, this.getCategoryColor(recipe.category));
            // Difficulty
            badgesHtml += this.renderBadge(recipe.difficulty, this.getDifficultyColor(recipe.difficulty));
            // Yield
            badgesHtml += `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-blue-200 bg-blue-50 text-blue-700"><i data-lucide="scale" class="w-3 h-3 mr-1"></i> ${recipe.baseYield} ${recipe.yieldUnit}</span>`;
            // Total Time
            const totalTime = recipe.productionTime + (recipe.restTime || 0);
            badgesHtml += `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-purple-200 bg-purple-50 text-purple-700"><i data-lucide="clock" class="w-3 h-3 mr-1"></i> ${this.formatTime(totalTime)} total</span>`;
            // Allergens Badge
            if (recipe.allergens && recipe.allergens.length > 0) {
                badgesHtml += `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-red-200 bg-red-50 text-red-700"><i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i> Allergens</span>`;
            }
            document.getElementById('info-badges').innerHTML = badgesHtml;


            // --- Times ---
            document.getElementById('info-time-prod').innerText = this.formatTime(recipe.productionTime);
            document.getElementById('info-shelf-life').innerText = recipe.shelfLife ? `${recipe.shelfLife}h` : '--';

            const restBox = document.getElementById('box-rest-time');
            if (recipe.restTime && recipe.restTime > 0) {
                restBox.classList.remove('hidden');
                document.getElementById('info-time-rest').innerText = this.formatTime(recipe.restTime);
            } else {
                restBox.classList.add('hidden');
            }

            // --- Dependencies ---
            const depContainer = document.getElementById('info-dependencies-container');
            if (recipe.dependencies && recipe.dependencies.length > 0) {
                depContainer.classList.remove('hidden');
                document.getElementById('info-dependencies-list').innerHTML = recipe.dependencies.map(dep => `
                    <div class="flex items-center justify-between bg-white rounded p-2 text-sm">
                        <div>
                            <span class="font-medium text-gray-900">${dep.recipeName}</span>
                            <span class="text-gray-600 ml-2">(${dep.quantity} ${dep.unit})</span>
                        </div>
                        <span class="text-xs text-gray-500">${this.formatTime(dep.leadTime)} before</span>
                    </div>
                `).join('');
            } else {
                depContainer.classList.add('hidden');
            }

            // --- Ingredients ---
            document.getElementById('count-ingredients').innerText = recipe.ingredients.length;
            document.getElementById('info-ingredients-list').innerHTML = recipe.ingredients.map(ing => `
                <div class="bg-gray-50 rounded-lg p-3 flex items-start justify-between hover:bg-gray-100 transition-colors">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 text-sm">${ing.name}</div>
                        ${ing.notes ? `<div class="text-xs text-gray-500 mt-0.5">${ing.notes}</div>` : ''}
                    </div>
                    <div class="text-right ml-3">
                        <div class="font-semibold text-gray-900 text-sm">${ing.quantity} ${ing.unit}</div>
                    </div>
                </div>
            `).join('');

            // --- Equipment ---
            document.getElementById('count-equipment').innerText = recipe.equipment.length;
            document.getElementById('info-equipment-list').innerHTML = recipe.equipment.map(eq => {
                const borderClass = eq.required ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-200';
                const requiredBadge = eq.required ? `<span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-red-200 bg-red-100 text-red-700 ml-auto">Required</span>` : '';
                return `
                <div class="rounded-lg p-3 border-2 ${borderClass}">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="${this.getEqIcon(eq.type)}" class="w-4 h-4"></i>
                        <span class="font-medium text-gray-900 text-sm">${eq.name}</span>
                        ${requiredBadge}
                    </div>
                    ${eq.notes ? `<p class="text-xs text-gray-600 mt-1">${eq.notes}</p>` : ''}
                </div>`;
            }).join('');

            // --- Steps ---
            document.getElementById('count-steps').innerText = `${recipe.steps.length} steps`;
            document.getElementById('info-steps-list').innerHTML = recipe.steps.map(step => `
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-[#D4A017] text-white rounded-full flex items-center justify-center font-bold text-sm">
                            ${step.stepNumber}
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 font-medium">${step.instruction}</p>
                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-600">
                                ${step.duration ? `<span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> ${this.formatTime(step.duration)}</span>` : ''}
                                ${step.temperature ? `<span class="flex items-center gap-1"><i data-lucide="thermometer" class="w-3 h-3"></i> ${step.temperature}°C</span>` : ''}
                                ${step.equipment ? `<span class="flex items-center gap-1"><i data-lucide="chef-hat" class="w-3 h-3"></i> ${step.equipment}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

            // --- Allergens & Tags ---
            const allergenSection = document.getElementById('section-allergens');
            if (recipe.allergens && recipe.allergens.length > 0) {
                allergenSection.classList.remove('hidden');
                document.getElementById('info-allergens-list').innerHTML = recipe.allergens.map(a =>
                    `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-red-200 bg-red-50 text-red-700">${a}</span>`
                ).join('');
            } else {
                allergenSection.classList.add('hidden');
            }

            const tagSection = document.getElementById('section-tags');
            if (recipe.tags && recipe.tags.length > 0) {
                tagSection.classList.remove('hidden');
                document.getElementById('info-tags-list').innerHTML = recipe.tags.map(t =>
                    `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-gray-200 bg-gray-100 text-gray-700">${t}</span>`
                ).join('');
            } else {
                tagSection.classList.add('hidden');
            }

            // --- Costing ---
            const costSection = document.getElementById('info-costing');
            if (recipe.totalCost) {
                costSection.classList.remove('hidden');
                document.getElementById('cost-materials').innerText = 'Rs. ' + recipe.materialCost?.toLocaleString();
                document.getElementById('cost-labor').innerText = 'Rs. ' + recipe.laborCost?.toLocaleString();
                document.getElementById('cost-overhead').innerText = 'Rs. ' + recipe.overheadCost?.toLocaleString();
                document.getElementById('cost-total').innerText = 'Rs. ' + recipe.totalCost?.toLocaleString();
                document.getElementById('cost-per-unit').innerText = 'Per unit: Rs. ' + Math.round(recipe.totalCost / recipe.baseYield).toLocaleString();
            } else {
                costSection.classList.add('hidden');
            }

            // Show and Animate
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
            lucide.createIcons();
        },

        close: function () {
            const modal = document.getElementById('recipe-modal');
            const backdrop = document.getElementById('recipe-modal-backdrop');
            const content = document.getElementById('recipe-modal-content');

            backdrop.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        },

        addToTimeline: function () {
            if (this.onAddCallback && this.currentRecipe) {
                this.onAddCallback(this.currentRecipe);
            }
            this.close();
        },

        // --- Helpers ---

        renderBadge: function (text, classes) {
            return `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 ${classes}">${text}</span>`;
        },

        getCategoryColor: function (category) {
            switch (category) {
                case 'Bread': return 'bg-amber-100 text-amber-700 border-amber-200';
                case 'Pastry': return 'bg-yellow-100 text-yellow-700 border-yellow-200';
                case 'Cake': return 'bg-pink-100 text-pink-700 border-pink-200';
                case 'Semi-Finished': return 'bg-indigo-100 text-indigo-700 border-indigo-200';
                case 'Decoration': return 'bg-purple-100 text-purple-700 border-purple-200';
                default: return 'bg-gray-100 text-gray-700 border-gray-200';
            }
        },

        getDifficultyColor: function (difficulty) {
            switch (difficulty) {
                case 'easy': return 'bg-green-100 text-green-700 border-green-200';
                case 'medium': return 'bg-blue-100 text-blue-700 border-blue-200';
                case 'hard': return 'bg-orange-100 text-orange-700 border-orange-200';
                case 'expert': return 'bg-red-100 text-red-700 border-red-200';
                default: return 'bg-gray-100 text-gray-700 border-gray-200';
            }
        },

        getEqIcon: function (type) {
            switch (type) {
                case 'oven': return 'flame';
                case 'mixer': return 'package';
                default: return 'chef-hat';
            }
        },

        formatTime: function (minutes) {
            if (minutes < 60) return `${minutes} min`;
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return mins > 0 ? `${hours}h ${mins}m` : `${hours}h`;
        }
    };
</script>