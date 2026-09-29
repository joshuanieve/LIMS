let analysisList = null;

export async function fetchAnalysisList() {

    // Return cached data if already fetched
    if (analysisList) {
        return analysisList;
    }

    const response = await fetch('/analysislist/data', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    });

    if (!response.ok) {
        throw new Error(
            `Failed to load analysis list: ${response.status}`
        );
    }

    analysisList = await response.json();

    return analysisList;
}


export function getAnalysisNames(analysisValue, analysisList) {

    if (!analysisValue) {
        return '-';
    }

    const ids = analysisValue
        .split(',')
        .map(id => Number(id.trim()));

    return ids
        .map(id => {

            const match = analysisList.find(
                item => item.analysisId === id
            );

            return match
                ? match.analyte
                : `Unknown (${id})`;
        })
        .join(', ');
}