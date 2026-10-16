{{-- Submission Information --}}
    <section class="lims-form-card">
        <div class="lims-form-card-header">
            <div>
                <p class="lims-form-eyebrow">Additional Requirements</p>
                <h2 class="lims-form-card-title">Submission Information</h2>
                <p class="lims-form-card-description">
                    Provide additional information required for sample handling and analysis.
                </p>
            </div>
            <div class="lims-form-step">04</div>
        </div>


        <div class="lims-question-list">
            {{-- HOLDING TIME --}}
            <div class="lims-question-card">
                <div class="lims-question-copy">
                    <span class="lims-question-number">01</span>
                    <div>
                        <p class="lims-question-text">
                            Do the submitted samples have an associated holding time requirement? *
                        </p>
                    </div>
                </div>

                <div class="lims-yes-no">
                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo1"
                            value="1"
                            class="lims-radio"
                            required
                        >
                        <span>Yes</span>
                    </label>

                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo1"
                            value="0"
                            class="lims-radio"
                            required
                        >
                        <span>No</span>
                    </label>
                </div>
            </div>


            {{-- HAZARDOUS COMPONENT --}}
            <div class="lims-question-card">
                <div class="lims-question-copy">
                    <span class="lims-question-number">02</span>

                    <div>
                        <p class="lims-question-text">
                            Do the submitted samples contain hazardous components? *
                        </p>
                    </div>
                </div>

                <div class="lims-yes-no">
                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo2"
                            value="1"
                            class="lims-radio"
                            required
                        >
                        <span>Yes</span>
                    </label>

                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo2"
                            value="0"
                            class="lims-radio"
                            required
                        >
                        <span>No</span>
                    </label>
                </div>
            </div>


            <div
                id="hazardousSpecifyContainer"
                class="lims-question-detail hidden"
            >
                <label class="lims-label">
                    Specify Hazardous Component
                </label>

                <input
                    type="text"
                    name="Subinfo2Specify"
                    id="hazardousSpecify"
                    class="lims-input"
                    placeholder="Specify hazardous component"
                >
            </div>


            {{-- UNCERTAINTY --}}
            <div class="lims-question-card">
                <div class="lims-question-copy">
                    <span class="lims-question-number">03</span>

                    <div>
                        <p class="lims-question-text">
                            Do the measurements need an associated uncertainty value? *
                        </p>
                    </div>
                </div>

                <div class="lims-yes-no">
                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo3"
                            value="1"
                            class="lims-radio"
                            required
                        >
                        <span>Yes</span>
                    </label>

                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo3"
                            value="0"
                            class="lims-radio"
                            required
                        >
                        <span>No</span>
                    </label>
                </div>
            </div>


            {{-- CONFORMITY --}}
            <div class="lims-question-card">
                <div class="lims-question-copy">
                    <span class="lims-question-number">04</span>

                    <div>
                        <p class="lims-question-text">
                            Do the submitted samples require a statement of conformity? *
                        </p>
                    </div>
                </div>

                <div class="lims-yes-no">
                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo4"
                            value="1"
                            class="lims-radio"
                            required
                        >
                        <span>Yes</span>
                    </label>

                    <label class="lims-answer-choice">
                        <input
                            type="radio"
                            name="Subinfo4"
                            value="0"
                            class="lims-radio"
                            required
                        >
                        <span>No</span>
                    </label>
                </div>
            </div>


            <div
                id="conformitySpecifyContainer"
                class="lims-question-detail hidden">

                <label class="lims-label">
                    Specify Conformity Requirement
                </label>

                <input
                        type="text"
                        name="Subinfo4Specify"
                        id="conformitySpecify"
                        class="lims-input"
                        placeholder="Specify conformity requirement"
                    >
                </div>


            {{-- SPECIAL INSTRUCTIONS --}}
            <div class="lims-field">
                <label class="lims-label">
                    Special Instructions
                </label>

                <textarea
                    name="Instruction"
                    class="lims-textarea"
                    rows="4"
                    placeholder="Enter special handling or analysis instructions..."
                ></textarea>
            </div>
        </div>
    </section>