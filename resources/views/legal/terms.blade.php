@extends ('layouts.app')

@section ('content')
    <div class="-m-3 min-h-[calc(100vh-73px)] bg-gray-100 sm:-m-6 lg:-m-8 dark:bg-darkBg">
        <section class="border-b border-gray-200 bg-white px-4 py-8 sm:px-6 sm:py-10 lg:px-10 dark:border-gray-800 dark:bg-darkPanel">
            <div class="mx-auto max-w-5xl">
                <p class="mb-3 text-xs font-bold tracking-widest text-green-600 uppercase dark:text-neon">Web3DShare Legal Terms</p>
                <h1 class="text-3xl leading-tight font-bold text-gray-950 sm:text-4xl lg:text-5xl dark:text-white">
                    EULA, Rules of Access, and Terms of Agreement
                </h1>
                <p class="mt-4 max-w-3xl leading-relaxed text-gray-600 dark:text-gray-400">These terms govern access to Web3DShare, including accounts, uploads, downloads, model pages, comments, reports, moderation tools, and any related community features. By creating an account, uploading content, browsing models, or using any feature of this website, you agree to follow these terms.</p>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-500">Last updated: June 18, 2026</p>
            </div>
        </section>

        <section class="px-3 py-6 sm:px-6 sm:py-10 lg:px-10">
            <div class="mx-auto grid max-w-5xl grid-cols-1 items-start gap-8 lg:grid-cols-[260px_1fr]">
                <aside
                    class="sticky top-24 hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm lg:block dark:border-gray-800 dark:bg-darkPanel"
                >
                    <h2 class="mb-4 text-xs font-bold tracking-widest text-gray-400 uppercase">Contents</h2>
                    <nav class="space-y-2 text-sm font-semibold text-gray-600 dark:text-gray-400">
                        <a href="#agreement" class="block hover:text-green-600 dark:hover:text-neon">Agreement</a>
                        <a href="#ownership" class="block hover:text-green-600 dark:hover:text-neon">Ownership</a>
                        <a href="#accounts" class="block hover:text-green-600 dark:hover:text-neon">Accounts</a>
                        <a href="#uploads" class="block hover:text-green-600 dark:hover:text-neon">Uploads</a>
                        <a href="#moderation" class="block hover:text-green-600 dark:hover:text-neon">Moderation</a>
                        <a href="#downloads" class="block hover:text-green-600 dark:hover:text-neon">Downloads</a>
                        <a href="#security" class="block hover:text-green-600 dark:hover:text-neon">Security</a>
                        <a href="#termination" class="block hover:text-green-600 dark:hover:text-neon">Termination</a>
                    </nav>
                </aside>

                <article class="space-y-6 text-gray-700 dark:text-gray-300">
                    <div
                        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <p class="text-sm leading-relaxed">This page is written to clearly describe Web3DShare's operating rules. It is not a substitute for advice from a licensed legal professional. If any part of these terms is unclear, the safest interpretation is the interpretation chosen by Web3DShare administrators and developers for the protection, stability, and continuity of the website.</p>
                    </div>

                    <section
                        id="agreement"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            1. Acceptance of Agreement
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>By accessing Web3DShare, registering an account, submitting a model, downloading a file, posting a comment, sending a report, or interacting with any page or feature, you confirm that you have read, understood, and accepted this EULA, Rules of Access, and Terms of Agreement.</p>
                            <p>If you do not agree with these terms, you must stop using the website immediately. Continued use of the website after changes are posted means you accept the revised terms. Web3DShare may update, rewrite, remove, or replace any section of these terms at any time when the administrators or developers consider it necessary.</p>
                            <p>Access to Web3DShare is a permission granted by the website administrators. It is not a permanent right, ownership interest, entitlement, contract of employment, partnership, or guarantee of continued service.</p>
                        </div>
                    </section>

                    <section
                        id="ownership"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            2. Website Ownership and Administrative Authority
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>All rights, control, authority, and final decision-making power related to Web3DShare belong to the website administrators and developers. This includes the website name, interface, source code, layout, visual identity, database structure, moderation system, storage structure, upload flow, ranking logic, public pages, private pages, admin panel, and all supporting systems.</p>
                            <p>Users do not acquire ownership, governance rights, voting rights, administrative rights, developer rights, moderation rights, or operational control over Web3DShare by registering, uploading files, commenting, reporting content, receiving a verified badge, or contributing activity to the platform.</p>
                            <p>For avoidance of doubt: there is no separate user-owned authority inside Web3DShare. The rights that govern the website are the rights of the administrators and developers. User access exists only as a limited, revocable permission to use features made available by the website.</p>
                            <p>Administrators and developers may make final decisions regarding content visibility, account status, feature access, upload limits, verified status, reports, data display, public presentation, technical maintenance, and any other matter related to the website.</p>
                        </div>
                    </section>

                    <section
                        id="accounts"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">3. Accounts and Identity</h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>You are responsible for the accuracy of the information you provide during registration and for maintaining the confidentiality of your account credentials. You may not impersonate another person, misrepresent your identity, use a misleading username, or create an account for abusive, deceptive, automated, or harmful purposes.</p>
                            <p>Web3DShare may restrict usernames, profile images, creator names, descriptions, comments, or other account details if they are confusing, offensive, misleading, infringing, spam-like, or otherwise unsuitable for the community.</p>
                            <p>Verified status, upload tier, creator presentation, and other labels are platform-managed privileges. They may be granted, denied, changed, suspended, or removed at the discretion of administrators and developers.</p>
                        </div>
                    </section>

                    <section
                        id="uploads"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            4. Uploads, Models, and Content License
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>When you upload a model, thumbnail, profile image, description, tag, category selection, comment, or any other content, you represent that you have the necessary permission to upload and share it. You may not upload stolen models, malware, deceptive files, unsafe files, illegal material, private data, or content that violates another party's rights.</p>
                            <p>By submitting content to Web3DShare, you grant Web3DShare, its administrators, and its developers a broad, worldwide, royalty-free, transferable, sublicensable, and non-exclusive license to host, store, process, resize, preview, display, distribute, moderate, remove, archive, analyze, and otherwise use that content for the operation and promotion of the website.</p>
                            <p>This license allows Web3DShare to show uploaded models on public pages, creator pages, search results, recommendation sections, admin panels, moderation views, thumbnails, previews, and other current or future website features.</p>
                            <p>Web3DShare is not required to host any upload permanently. Administrators and developers may remove, hide, reclassify, restrict, or disable any content if they consider it harmful, low quality, infringing, misleading, broken, unsafe, spam-like, or inconsistent with the purpose of the website.</p>
                        </div>
                    </section>

                    <section
                        id="conduct"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">5. Community Conduct</h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>You agree not to harass other users, post abusive comments, spam reports, manipulate stars or views, attack the website, upload repeated junk content, bypass limits, scrape aggressively, reverse engineer protected systems, or interfere with other people's use of the platform.</p>
                            <p>Reports must be submitted in good faith. A report is a request for review, not a guarantee that administrators will take the action requested by the reporter. False, malicious, repetitive, or weaponized reports may lead to account restrictions.</p>
                            <p>Comments and public interactions should remain constructive. Web3DShare may remove comments or restrict users when communication becomes hostile, misleading, exploitative, or disruptive.</p>
                        </div>
                    </section>

                    <section
                        id="moderation"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            6. Moderation and Enforcement
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>Administrators, moderators, and developers may review uploads, reports, comments, user profiles, verified requests, suspicious activity, and technical logs. They may approve, reject, edit visibility, resolve reports, delete models, limit access, or suspend accounts when needed.</p>
                            <p>Moderation decisions may be made with or without notice. Web3DShare is not required to provide a public explanation for every decision, especially where security, abuse prevention, privacy, or operational stability is involved.</p>
                            <p>Where possible, Web3DShare may provide alerts, errors, or feedback for failed forms and rejected actions. However, the absence of a detailed explanation does not limit administrator or developer authority.</p>
                        </div>
                    </section>

                    <section
                        id="downloads"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            7. Downloads and Third-Party Use
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>Models and files may be made available for download depending on the website's current features and permissions. Downloading content does not mean Web3DShare guarantees the file's quality, safety, accuracy, compatibility, licensing status, or suitability for any specific use.</p>
                            <p>You are responsible for checking downloaded files before use. Web3DShare is not responsible for damage, data loss, software issues, intellectual property disputes, or other consequences arising from downloaded or uploaded content.</p>
                            <p>Do not redistribute, resell, reupload, or use content in a way that violates applicable law, creator restrictions, or these terms.</p>
                        </div>
                    </section>

                    <section
                        id="security"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            8. Security, Availability, and Data
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>Web3DShare may use databases, storage providers, hosting providers, APIs, queues, logs, and other infrastructure to operate the website. Service interruptions, maintenance, provider outages, data delays, upload failures, or temporary feature failures may occur.</p>
                            <p>You may not attempt to access admin-only areas, abuse endpoints, extract secrets, bypass authentication, overload uploads, perform automated attacks, or exploit any technical weakness. If you discover a security problem, you should report it responsibly and avoid public exploitation.</p>
                            <p>Administrators and developers may inspect technical data needed to debug, secure, moderate, or maintain the website. This may include account identifiers, upload metadata, report metadata, request behavior, storage paths, and error logs.</p>
                        </div>
                    </section>

                    <section
                        id="termination"
                        class="scroll-mt-24 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">
                            9. Suspension, Removal, and Termination
                        </h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>Web3DShare may suspend, restrict, remove, or terminate access to any account or content at any time if the administrators or developers believe it is necessary for safety, legal compliance, abuse prevention, community quality, operational stability, or protection of the website.</p>
                            <p>When access is removed, the user does not gain a right to compensation, continued hosting, restoration, data migration, or public explanation. Web3DShare may preserve, delete, archive, or anonymize related data according to technical needs and administrator judgment.</p>
                            <p>Any section of these terms that reasonably should survive termination will continue to apply, including ownership, content license, moderation authority, limitations of liability, and administrator rights.</p>
                        </div>
                    </section>

                    <section
                        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h2 class="mb-4 text-xl font-bold text-gray-950 sm:text-2xl dark:text-white">10. Final Statement</h2>
                        <div class="space-y-4 leading-relaxed">
                            <p>Web3DShare exists as a website controlled and maintained by its administrators and developers. Users are welcome to participate only under the permissions, limits, rules, and decisions set by those administrators and developers.</p>
                            <p>If any part of these terms is found unenforceable, the remaining sections remain in effect. The administrators and developers may interpret, enforce, revise, or replace these terms as needed to protect the website and keep the service usable.</p>
                        </div>
                    </section>
                </article>
            </div>
        </section>
    </div>
@endsection
