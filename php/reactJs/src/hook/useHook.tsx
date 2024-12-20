import { useEffect, useState } from 'react'

/**
 * 
 * This hook do increment the initial value by step
 * 
 * @param initialValue number of initial value
 * @param step number of step to increment
 * @returns [] 
 */
export const useIncrement = (initialValue: number = 0, step: number = 1): [count: number, increment: Function] => {

    const [state, setState] = useState(initialValue);
    const increment = () => {
        setState(c => c + step);
    }

    return [
        state,
        increment
    ];
}

/**
 * This hook is a toggle
 * @param initialValue initial state of toggle
 * @returns []
 */
export const useToggle = (initialValue: boolean): [count: boolean, increment: Function] => {
    const [state, setState] = useState(initialValue);

    const increment = () => {
        setState(c => !c);
    }

    return [
        state,
        increment
    ];
}


/**
 * 
 * This hook do increment the initial value by step
 * 
 * @param initialValue number of initial value
 * @param step number of step to increment
 * @returns [] 
 */
export const useAutoIncrement = (initialValue: number = 0, step: number = 1, interval: number = 1000): number => {

    const [count, increment] = useIncrement(initialValue, step);

    useEffect(() => {

        // will monted
        const timer = setInterval(() => {
            increment();
        }, interval);

        // will Unmonted 
        return function () {
            clearInterval(timer)
        };

    }, [increment, interval])


    return count;
}


/**
 * 
 * 
 * @param url 
 * @returns 
 */
export const useFetch = (url: string): [loading: boolean, items: any[]] => {

    const [state, setState] = useState({
        items: [],
        loading: true
    })

    useEffect(() => {


        (async () => {
            const response = await fetch(url);
            const responseData = await response.json();

            if (response.ok) {
                setState(s => ({ ...s, loading: false, items: responseData }))
            } else {
                alert(JSON.stringify(responseData));
                setState(s => ({ ...s, loading: false }))
            }
        })()
    }, [url])

    return [
        state.loading,
        state.items
    ]
}

