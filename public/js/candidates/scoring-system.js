/**
 * Scoring System for Candidate Evaluation
 * 
 * This script handles the dynamic calculation of scores based on criteria types and categories.
 * It provides real-time feedback on the total score and score distribution.
 */

class ScoringSystem {
    constructor() {
        this.criteriaScores = new Map(); // Store scores by criteria ID
        this.categoryScores = new Map(); // Store scores by category
        this.maxPossibleScore = 0;
        this.maxPossibleWeight = 0;
        
        this.initializeEventListeners();
        this.initializeFromDOM();
    }

    /**
     * Initialize event listeners for dynamic updates
     */
    initializeEventListeners() {
        // Listen for changes in criteria scores
        $(document).on('change', '[data-criteria-id]', (e) => {
            const criteriaId = $(e.target).data('criteria-id');
            const score = parseFloat($(e.target).val()) || 0;
            this.updateCriteriaScore(criteriaId, score);
            this.updateUI();
        });

        // Listen for changes in category scores
        $(document).on('change', '[data-category]', (e) => {
            const category = $(e.target).data('category');
            const score = parseInt($(e.target).val()) || 0;
            this.updateCategoryScore(category, score);
            this.updateUI();
        });
    }

    /**
     * Initialize scores from the DOM
     */
    initializeFromDOM() {
        // Initialize criteria scores
        $('[data-criteria-id]').each((_, element) => {
            const criteriaId = $(element).data('criteria-id');
            const score = parseFloat($(element).val()) || 0;
            this.criteriaScores.set(criteriaId, score);
        });

        // Initialize category scores
        $('[data-category]').each((_, element) => {
            const category = $(element).data('category');
            const score = parseInt($(element).val()) || 0;
            this.categoryScores.set(category, score);
        });

        // Calculate max possible values
        this.calculateMaxPossibleValues();
        this.updateUI();
    }


    /**
     * Update score for a specific criteria
     * @param {number} criteriaId - The ID of the criteria
     * @param {number} score - The score to set (0-100)
     */
    updateCriteriaScore(criteriaId, score) {
        // Ensure score is within valid range (0-100)
        score = Math.max(0, Math.min(100, score));
        this.criteriaScores.set(criteriaId, score);
    }

    /**
     * Update score for a specific category
     * @param {string} category - The category name
     * @param {number} score - The score to set (0-100)
     */
    updateCategoryScore(category, score) {
        // Ensure score is within valid range (0-100)
        score = Math.max(0, Math.min(100, score));
        this.categoryScores.set(category, score);
    }

    /**
     * Calculate the scored value for a specific criteria
     * @param {number} criteriaId - The ID of the criteria
     * @returns {number} The calculated score
     */
    calculateWeightedScore(criteriaId) {
        const criteriaScore = this.criteriaScores.get(criteriaId) || 0;
        const criteriaElement = $(`[data-criteria-id="${criteriaId}"]`);
        const category = criteriaElement.data('category');
        const categoryScore = this.categoryScores.get(category) || 0;
        
        return (criteriaScore * categoryScore) / 100; // Using criteriaScore * categoryScore / 100 for the calculation
    }

    /**
     * Calculate the total score across all criteria
     * @returns {number} The total score
     */
    calculateTotalScore() {
        let total = 0;
        this.criteriaScores.forEach((_, criteriaId) => {
            total += this.calculateWeightedScore(criteriaId);
        });
        return total;
    }

    /**
     * Calculate the total score across all categories
     * @returns {number} The total score
     */
    calculateTotalCategoryScore() {
        let total = 0;
        this.categoryScores.forEach(score => {
            total += score;
        });
        return total;
    }

    /**
     * Calculate the maximum possible score and weight
     */
    calculateMaxPossibleValues() {
        this.maxPossibleScore = 100; // Max score per criteria is 100
        this.maxPossibleWeight = 100; // Max total weight is 100%
    }

    /**
     * Update the UI with current scores and calculations
     */
    updateUI() {
        const totalScore = this.calculateTotalScore();
        this.updateScoreDistribution();
        
        // Update total score display
        $('.total-score').text(totalScore.toFixed(2));
        
        // Update progress bar for total score
        const scorePercentage = this.calculateMaxPossibleScore() > 0 ? (totalScore / this.calculateMaxPossibleScore()) * 100 : 0;
        $('#total-score-progress').css('width', `${scorePercentage}%`);
        $('#total-score-value').text(`${totalScore}%`);
        
        // Update score validation message
        if (totalScore > 100) {
            $('#score-validation').removeClass('text-success').addClass('text-danger')
                .text(`Total exceeds 100% by ${totalScore - 100}%`);
        } else if (totalScore < 100) {
            $('#score-validation').removeClass('text-danger').addClass('text-warning')
                .text(`Total is ${100 - totalScore}% under 100%`);
        } else {
            $('#score-validation').removeClass('text-danger text-warning').addClass('text-success')
                .text('Total is exactly 100%');
        }
    }

    /**
     * Update the score distribution display
     */
    updateScoreDistribution() {
        const totalScore = this.calculateTotalScore();
        
        // Update score distribution display
        $('[data-category]').each((_, element) => {
            const category = $(element).data('category');
            const score = this.categoryScores.get(category) || 0;
            const percentage = totalScore > 0 ? Math.round((score / totalScore) * 100) : 0;
            
            $(element).siblings('.score-percentage').text(`${percentage}%`);
        });
    }

    /**
     * Update a progress bar element
     * @param {string} selector - The CSS selector for the progress bar
     * @param {number} value - The current value
     * @param {number} max - The maximum value
     */
    updateProgressBar(selector, value, max) {
        const percentage = Math.min(100, (value / max) * 100);
        $(`${selector} .progress-bar`).css('width', `${percentage}%`);
        
        // Update color based on percentage
        const progressBar = $(`${selector} .progress-bar`);
        progressBar.removeClass('bg-success bg-warning bg-danger');
        
        if (percentage >= 70) {
            progressBar.addClass('bg-success');
        } else if (percentage >= 30) {
            progressBar.addClass('bg-warning');
        } else {
            progressBar.addClass('bg-danger');
        }
    }
}

// Initialize the scoring system when the document is ready
$(document).ready(() => {
    // Only initialize if we're on a page with scoring elements
    if ($('[data-criteria-id]').length > 0 || $('[data-category]').length > 0) {
        window.scoringSystem = new ScoringSystem();
    }
});
